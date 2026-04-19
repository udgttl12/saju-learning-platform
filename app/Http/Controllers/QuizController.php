<?php

namespace App\Http\Controllers;

use App\Models\LessonAttempt;
use App\Models\QuizSet;
use App\Models\User;
use App\Services\GuestLearningService;
use App\Services\LearningProgressService;
use App\Services\QuizService;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        private QuizService $quizService,
        private ReviewService $reviewService,
        private LearningProgressService $learningProgressService,
        private GuestLearningService $guestLearningService,
    ) {}

    public function show(string $code)
    {
        $quizSet = $this->quizService->getQuizSetByCode($code);
        $requestUser = request()->user();

        $this->authorizeQuizAccess($requestUser, $quizSet);

        $bestAttempt = $requestUser
            ? $this->learningProgressService->getBestQuizAttempt($requestUser, $quizSet)
            : $this->toAttemptSummaryObject($this->guestLearningService->getQuizAttemptSummary($quizSet));
        $isGuestPreview = !$requestUser;

        return view('quiz.show', compact('quizSet', 'bestAttempt', 'isGuestPreview'));
    }

    public function submit(string $code, Request $request)
    {
        $quizSet = $this->quizService->getQuizSetByCode($code);
        $this->authorizeQuizAccess($request->user(), $quizSet);

        $request->validate([
            'answers' => 'required|array',
        ]);

        $results = $this->quizService->gradeSubmission($quizSet, $request->answers);
        $score = $this->quizService->calculateScore($results);
        $weakPoints = $this->quizService->summarizeWeakPoints($results);
        $attempt = null;
        $attemptId = null;
        $createdReviewCards = 0;
        $isGuestPreview = !$request->user();

        if ($request->user()) {
            $attempt = $this->quizService->recordAttempt($request->user(), $quizSet, $results, $score, $request->answers);
            $attemptId = $attempt->id;

            $createdReviewCards = $this->reviewService->createFromQuizResult($request->user(), $results, $quizSet);
            $this->syncLessonQuizProgress($request->user()->id, $quizSet, $score['percentage']);

            if ($quizSet->scope_type === 'track' && $quizSet->learningTrack && $attempt->passed) {
                $this->learningProgressService->markTrackExamPassed($request->user(), $quizSet->learningTrack, $score['percentage']);
            }
        } else {
            $attempt = $this->toAttemptSummaryObject(
                $this->guestLearningService->storeQuizAttempt($quizSet, $score, $weakPoints)
            );
        }

        session()->put("quiz_result_{$code}", [
            'results' => $results,
            'score' => $score,
            'quiz_set' => $quizSet,
            'attempt_id' => $attemptId,
            'attempt_summary' => $attempt,
            'weak_points' => $weakPoints,
            'created_review_cards' => $createdReviewCards,
            'is_guest' => $isGuestPreview,
        ]);

        return redirect()->route('quiz.result', $code);
    }

    public function result(string $code)
    {
        $data = session("quiz_result_{$code}");

        if (!$data) {
            return redirect()->route('quiz.show', $code);
        }

        $isGuestPreview = (bool) ($data['is_guest'] ?? false);
        $attempt = isset($data['attempt_id'])
            ? auth()->user()?->quizAttempts()->with('quizSet')->find($data['attempt_id'])
            : ($isGuestPreview ? $data['attempt_summary'] ?? null : null);
        $weakPoints = collect($attempt?->weak_points_json ?? $data['weak_points'] ?? []);
        $recommendedLessons = $weakPoints
            ->pluck('review_lesson_code')
            ->filter()
            ->unique()
            ->values();
        $recommendedLessonMap = $recommendedLessons->isEmpty()
            ? collect()
            : \App\Models\Lesson::whereIn('code', $recommendedLessons)->get()->keyBy('code');

        return view('quiz.result', [
            'results' => $data['results'],
            'score' => $data['score'],
            'quizSet' => $data['quiz_set'],
            'attempt' => $attempt,
            'weakPoints' => $weakPoints,
            'recommendedLessonMap' => $recommendedLessonMap,
            'createdReviewCards' => $data['created_review_cards'] ?? 0,
            'isGuestPreview' => $isGuestPreview,
        ]);
    }

    private function authorizeQuizAccess(?User $user, QuizSet $quizSet): void
    {
        if (!$user) {
            if ($quizSet->scope_type !== 'lesson' || !$quizSet->lesson) {
                abort(403);
            }

            $quizSet->loadMissing('lesson.learningTrack');

            $trackState = $this->guestLearningService->getTrackState($quizSet->lesson->learningTrack);
            if (!$trackState['unlocked']) {
                abort(403);
            }

            $lessonState = $this->guestLearningService->getLessonState(
                $quizSet->lesson,
                $this->guestLearningService->getCompletedLessonCodes($quizSet->lesson->learningTrack)
            );

            if (!$lessonState['unlocked']) {
                abort(403);
            }

            return;
        }

        if ($quizSet->lesson) {
            $enrollment = $user->trackEnrollments()
                ->where('learning_track_id', $quizSet->lesson->learning_track_id)
                ->first();

            if (!$enrollment) {
                abort(403);
            }

            $lessonState = $this->learningProgressService->getLessonState($user, $quizSet->lesson);
            if (!$lessonState['unlocked']) {
                abort(403);
            }
        }

        if ($quizSet->scope_type === 'track' && $quizSet->learningTrack) {
            $trackState = $this->learningProgressService->getTrackState($user, $quizSet->learningTrack);

            if (!$trackState['enrollment']) {
                abort(403);
            }

            if (!$trackState['unlocked']) {
                abort(403);
            }

            $completedLessonIds = $this->learningProgressService->getCompletedLessonIds($user, $quizSet->learningTrack);
            $publishedLessonsCount = $quizSet->learningTrack->lessons()->where('publish_status', 'published')->count();

            if ($publishedLessonsCount > count($completedLessonIds)) {
                abort(403);
            }
        }
    }

    private function syncLessonQuizProgress(int $userId, QuizSet $quizSet, int $percentage): void
    {
        if (!$quizSet->lesson_id) {
            return;
        }

        $attempt = LessonAttempt::firstOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $quizSet->lesson_id,
            ],
            [
                'status' => 'in_progress',
                'progress_percent' => 0,
                'first_started_at' => now(),
                'last_accessed_at' => now(),
            ]
        );

        $attempt->update([
            'latest_score' => $percentage,
            'best_score' => max((int) ($attempt->best_score ?? 0), $percentage),
            'last_accessed_at' => now(),
        ]);
    }

    private function toAttemptSummaryObject(?array $attemptSummary): ?object
    {
        if (!$attemptSummary) {
            return null;
        }

        return (object) $attemptSummary;
    }
}

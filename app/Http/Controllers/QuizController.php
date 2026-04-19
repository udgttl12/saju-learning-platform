<?php

namespace App\Http\Controllers;

use App\Models\LessonAttempt;
use App\Models\QuizSet;
use App\Models\User;
use App\Services\QuizService;
use App\Services\LearningProgressService;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        private QuizService $quizService,
        private ReviewService $reviewService,
        private LearningProgressService $learningProgressService,
    ) {}

    public function show(string $code)
    {
        $quizSet = $this->quizService->getQuizSetByCode($code);
        $requestUser = request()->user();

        $this->authorizeQuizAccess($requestUser, $quizSet);

        $bestAttempt = $requestUser
            ? $this->learningProgressService->getBestQuizAttempt($requestUser, $quizSet)
            : null;

        return view('quiz.show', compact('quizSet', 'bestAttempt'));
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
        $attempt = $this->quizService->recordAttempt($request->user(), $quizSet, $results, $score, $request->answers);

        $createdReviewCards = $this->reviewService->createFromQuizResult($request->user(), $results, $quizSet);
        $this->syncLessonQuizProgress($request->user()->id, $quizSet, $score['percentage']);

        if ($quizSet->scope_type === 'track' && $quizSet->learningTrack && $attempt->passed) {
            $this->learningProgressService->markTrackExamPassed($request->user(), $quizSet->learningTrack, $score['percentage']);
        }

        session()->put("quiz_result_{$code}", [
            'results' => $results,
            'score' => $score,
            'quiz_set' => $quizSet,
            'attempt_id' => $attempt->id,
            'created_review_cards' => $createdReviewCards,
        ]);

        return redirect()->route('quiz.result', $code);
    }

    public function result(string $code)
    {
        $data = session("quiz_result_{$code}");

        if (!$data) {
            return redirect()->route('quiz.show', $code);
        }

        $attempt = isset($data['attempt_id'])
            ? auth()->user()?->quizAttempts()->with('quizSet')->find($data['attempt_id'])
            : null;
        $recommendedLessons = collect($attempt?->weak_points_json ?? [])
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
            'recommendedLessonMap' => $recommendedLessonMap,
            'createdReviewCards' => $data['created_review_cards'] ?? 0,
        ]);
    }

    private function authorizeQuizAccess(?User $user, QuizSet $quizSet): void
    {
        if (!$user) {
            abort(403);
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
}

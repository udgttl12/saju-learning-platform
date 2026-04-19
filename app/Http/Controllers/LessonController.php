<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonAttempt;
use App\Models\QuizSet;
use App\Models\TrackEnrollment;
use App\Services\GuestLearningService;
use App\Services\LearningProgressService;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function __construct(
        private LearningProgressService $learningProgressService,
        private GuestLearningService $guestLearningService,
    ) {}

    public function show(string $slug)
    {
        $lesson = Lesson::where('slug', $slug)
            ->where('publish_status', 'published')
            ->with(['steps' => function ($q) {
                $q->orderBy('sort_order');
            }, 'learningTrack', 'hanjaChars'])
            ->firstOrFail();

        $user = Auth::user();
        $isGuestPreview = !$user;
        $attempt = null;
        $isLessonCompleted = false;

        if ($user) {
            $enrollment = TrackEnrollment::where('user_id', $user->id)
                ->where('learning_track_id', $lesson->learning_track_id)
                ->first();

            if (!$enrollment) {
                return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                    ->with('error', '트랙 등록 후 레슨을 시작할 수 있어요.');
            }

            $lessonState = $this->learningProgressService->getLessonState($user, $lesson);
            if (!$lessonState['unlocked']) {
                return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                    ->with('error', $lessonState['reason']);
            }

            $attempt = LessonAttempt::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'status' => 'in_progress',
                    'progress_percent' => 0,
                    'first_started_at' => now(),
                    'last_accessed_at' => now(),
                ]
            );

            $attempt->update(['last_accessed_at' => now()]);
            $enrollment->update(['last_accessed_at' => now()]);
            $isLessonCompleted = $attempt->status === 'completed';
        } else {
            $trackState = $this->guestLearningService->getTrackState($lesson->learningTrack);
            if (!$trackState['unlocked']) {
                return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                    ->with('error', $trackState['reason']);
            }

            $completedLessonCodes = $this->guestLearningService->getCompletedLessonCodes($lesson->learningTrack);
            $lessonState = $this->guestLearningService->getLessonState($lesson, $completedLessonCodes);
            if (!$lessonState['unlocked']) {
                return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                    ->with('error', $lessonState['reason']);
            }

            $isLessonCompleted = in_array($lesson->code, $completedLessonCodes, true);
        }

        $quizSets = collect();
        foreach ($lesson->steps as $step) {
            if ($step->payload_json && isset($step->payload_json['quiz_set_code'])) {
                $code = $step->payload_json['quiz_set_code'];
                $quizSet = QuizSet::where('code', $code)
                    ->where('publish_status', 'published')
                    ->with('items')
                    ->first();
                if ($quizSet) {
                    $quizSets[$code] = $quizSet;
                }
            }
        }

        $hanjaChars = $lesson->hanjaChars;
        $quizProgress = [];

        foreach ($quizSets as $code => $quizSet) {
            $attemptSummary = $user
                ? $this->learningProgressService->getBestQuizAttempt($user, $quizSet)
                : $this->guestLearningService->getQuizAttemptSummary($quizSet);

            $quizProgress[$code] = [
                'passed' => $attemptSummary['passed'] ?? $attemptSummary?->passed ?? false,
                'best_score' => $attemptSummary['score_percentage'] ?? $attemptSummary?->score_percentage,
                'attempted' => $attemptSummary !== null,
            ];
        }

        return view('lessons.show', compact(
            'lesson',
            'attempt',
            'quizSets',
            'hanjaChars',
            'quizProgress',
            'isGuestPreview',
            'isLessonCompleted',
        ));
    }

    public function complete(string $slug)
    {
        $lesson = Lesson::where('slug', $slug)
            ->where('publish_status', 'published')
            ->with(['steps', 'learningTrack'])
            ->firstOrFail();

        $user = Auth::user();
        $lessonState = $user
            ? $this->learningProgressService->getLessonState($user, $lesson)
            : $this->guestLearningService->getLessonState(
                $lesson,
                $this->guestLearningService->getCompletedLessonCodes($lesson->learningTrack)
            );

        if (!$lessonState['unlocked']) {
            return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                ->with('error', $lessonState['reason']);
        }

        $attempt = null;
        if ($user) {
            $attempt = LessonAttempt::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'status' => 'in_progress',
                    'progress_percent' => 0,
                    'first_started_at' => now(),
                    'last_accessed_at' => now(),
                ]
            );
        }

        $quizSetCodes = $lesson->steps
            ->filter(fn ($step) => $step->step_type === 'quiz' && !empty($step->payload_json['quiz_set_code']))
            ->pluck('payload_json.quiz_set_code')
            ->filter()
            ->unique()
            ->values();

        foreach ($quizSetCodes as $quizSetCode) {
            $quizSet = QuizSet::where('code', $quizSetCode)
                ->where('publish_status', 'published')
                ->first();

            if (!$quizSet) {
                continue;
            }

            $hasPassedQuiz = $user
                ? $this->learningProgressService->hasPassedQuizSet($user, $quizSet)
                : $this->guestLearningService->hasPassedQuizSet($quizSet);

            if (!$hasPassedQuiz) {
                return redirect()->route('lessons.show', $lesson->slug)
                    ->with('error', '레슨 안의 퀴즈를 먼저 통과해야 완료할 수 있어요.');
            }
        }

        if ($attempt) {
            $attempt->update([
                'status' => 'completed',
                'progress_percent' => 100,
                'completed_at' => now(),
                'last_accessed_at' => now(),
            ]);

            $this->learningProgressService->syncTrackEnrollment($user, $lesson->learningTrack);

            return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                ->with('success', '레슨을 완료했어요.');
        }

        $this->guestLearningService->completeLesson($lesson);

        return redirect()->route('tracks.show', $lesson->learningTrack->slug)
            ->with('success', '레슨을 완료했어요. 비회원 진행 상황은 이 브라우저에 임시 저장됩니다.');
    }
}

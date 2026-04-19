<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonAttempt;
use App\Models\QuizSet;
use App\Models\TrackEnrollment;
use App\Services\LearningProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function __construct(
        private LearningProgressService $learningProgressService
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

        // 트랙 등록 여부 확인
        $enrollment = TrackEnrollment::where('user_id', $user->id)
            ->where('learning_track_id', $lesson->learning_track_id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                ->with('error', '먼저 트랙에 등록해주세요.');
        }

        $lessonState = $this->learningProgressService->getLessonState($user, $lesson);
        if (!$lessonState['unlocked']) {
            return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                ->with('error', $lessonState['reason']);
        }

        // LessonAttempt 생성 또는 조회
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

        // 마지막 접근 시간 업데이트
        $attempt->update(['last_accessed_at' => now()]);

        // 트랙 마지막 접근 시간도 업데이트
        $enrollment->update(['last_accessed_at' => now()]);

        // 스텝에서 quiz_set_code가 있는 경우 QuizSet + QuizItems 미리 로드
        $quizSets = collect();
        foreach ($lesson->steps as $step) {
            if ($step->payload_json && isset($step->payload_json['quiz_set_code'])) {
                $code = $step->payload_json['quiz_set_code'];
                $quizSet = QuizSet::where('code', $code)
                    ->with('items')
                    ->first();
                if ($quizSet) {
                    $quizSets[$code] = $quizSet;
                }
            }
        }

        // 레슨에 연결된 한자들 (이미 with에서 로드되지만 명시적으로 전달)
        $hanjaChars = $lesson->hanjaChars;
        $quizProgress = [];

        foreach ($quizSets as $code => $quizSet) {
            $bestAttempt = $this->learningProgressService->getBestQuizAttempt($user, $quizSet);

            $quizProgress[$code] = [
                'passed' => $bestAttempt?->passed ?? false,
                'best_score' => $bestAttempt?->score_percentage,
                'attempted' => $bestAttempt !== null,
            ];
        }

        return view('lessons.show', compact('lesson', 'attempt', 'quizSets', 'hanjaChars', 'quizProgress'));
    }

    public function complete(string $slug)
    {
        $lesson = Lesson::where('slug', $slug)
            ->where('publish_status', 'published')
            ->with(['steps', 'learningTrack'])
            ->firstOrFail();

        $user = Auth::user();
        $lessonState = $this->learningProgressService->getLessonState($user, $lesson);

        if (!$lessonState['unlocked']) {
            return redirect()->route('tracks.show', $lesson->learningTrack->slug)
                ->with('error', $lessonState['reason']);
        }

        $attempt = LessonAttempt::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

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

            if ($quizSet && !$this->learningProgressService->hasPassedQuizSet($user, $quizSet)) {
                return redirect()->route('lessons.show', $lesson->slug)
                    ->with('error', '레슨 완료 전에 연결된 퀴즈를 통과해야 합니다.');
            }
        }

        if ($attempt) {
            $attempt->update([
                'status' => 'completed',
                'progress_percent' => 100,
                'completed_at' => now(),
                'last_accessed_at' => now(),
            ]);
        }

        $this->learningProgressService->syncTrackEnrollment($user, $lesson->learningTrack);

        return redirect()->route('tracks.show', $lesson->learningTrack->slug)
            ->with('success', '레슨을 완료했습니다!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonAttempt;
use App\Models\QuizSet;
use App\Models\TrackEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
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

        return view('lessons.show', compact('lesson', 'attempt', 'quizSets', 'hanjaChars'));
    }

    public function complete(string $slug)
    {
        $lesson = Lesson::where('slug', $slug)
            ->where('publish_status', 'published')
            ->firstOrFail();

        $user = Auth::user();

        $attempt = LessonAttempt::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($attempt) {
            $attempt->update([
                'status' => 'completed',
                'progress_percent' => 100,
                'completed_at' => now(),
                'last_accessed_at' => now(),
            ]);

            // 트랙 진행률 업데이트
            $track = $lesson->learningTrack;
            $totalLessons = $track->lessons()->where('publish_status', 'published')->count();
            $completedLessons = LessonAttempt::where('user_id', $user->id)
                ->whereIn('lesson_id', $track->lessons()->pluck('id'))
                ->where('status', 'completed')
                ->count();

            $enrollment = TrackEnrollment::where('user_id', $user->id)
                ->where('learning_track_id', $track->id)
                ->first();

            if ($enrollment && $totalLessons > 0) {
                $progress = round(($completedLessons / $totalLessons) * 100, 2);
                $enrollment->update([
                    'progress_percent' => $progress,
                    'last_accessed_at' => now(),
                    'completed_at' => $progress >= 100 ? now() : null,
                    'status' => $progress >= 100 ? 'completed' : 'active',
                ]);
            }
        }

        return redirect()->route('tracks.show', $lesson->learningTrack->slug)
            ->with('success', '레슨을 완료했습니다!');
    }
}

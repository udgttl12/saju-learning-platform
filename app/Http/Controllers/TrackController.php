<?php

namespace App\Http\Controllers;

use App\Models\LearningTrack;
use App\Models\TrackEnrollment;
use App\Models\LessonAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function index()
    {
        $tracks = LearningTrack::where('publish_status', 'published')
            ->orderBy('sort_order')
            ->withCount('lessons')
            ->get();

        // 로그인 상태라면 등록 정보도 가져오기
        $enrolledTrackIds = [];
        if (Auth::check()) {
            $enrolledTrackIds = TrackEnrollment::where('user_id', Auth::id())
                ->pluck('learning_track_id')
                ->toArray();
        }

        return view('tracks.index', compact('tracks', 'enrolledTrackIds'));
    }

    public function show(string $slug)
    {
        $track = LearningTrack::where('slug', $slug)
            ->where('publish_status', 'published')
            ->with(['lessons' => function ($q) {
                $q->where('publish_status', 'published')->orderBy('sort_order');
            }])
            ->firstOrFail();

        $enrollment = null;
        $completedLessonIds = [];

        if (Auth::check()) {
            $enrollment = TrackEnrollment::where('user_id', Auth::id())
                ->where('learning_track_id', $track->id)
                ->first();

            if ($enrollment) {
                $completedLessonIds = LessonAttempt::where('user_id', Auth::id())
                    ->whereIn('lesson_id', $track->lessons->pluck('id'))
                    ->where('status', 'completed')
                    ->pluck('lesson_id')
                    ->toArray();
            }
        }

        return view('tracks.show', compact('track', 'enrollment', 'completedLessonIds'));
    }

    public function enroll(string $slug)
    {
        $track = LearningTrack::where('slug', $slug)
            ->where('publish_status', 'published')
            ->firstOrFail();

        $user = Auth::user();

        // 이미 등록되어 있으면 무시
        $exists = TrackEnrollment::where('user_id', $user->id)
            ->where('learning_track_id', $track->id)
            ->exists();

        if (!$exists) {
            TrackEnrollment::create([
                'user_id' => $user->id,
                'learning_track_id' => $track->id,
                'status' => 'active',
                'progress_percent' => 0,
                'started_at' => now(),
                'last_accessed_at' => now(),
            ]);
        }

        return redirect()->route('tracks.show', $slug)
            ->with('success', '트랙에 등록되었습니다!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LearningTrack;
use App\Models\TrackEnrollment;
use App\Services\LearningProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function __construct(
        private LearningProgressService $learningProgressService
    ) {}

    public function index()
    {
        $tracks = LearningTrack::where('publish_status', 'published')
            ->orderBy('sort_order')
            ->withCount('lessons')
            ->get();

        $trackStates = $this->learningProgressService->getTrackStates(Auth::user(), $tracks);
        $enrolledTrackIds = collect($trackStates)
            ->filter(fn ($state) => $state['enrollment'] !== null)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return view('tracks.index', compact('tracks', 'enrolledTrackIds', 'trackStates'));
    }

    public function show(string $slug)
    {
        $track = LearningTrack::where('slug', $slug)
            ->where('publish_status', 'published')
            ->with(['lessons' => function ($q) {
                $q->where('publish_status', 'published')->orderBy('sort_order');
            }])
            ->firstOrFail();

        $trackState = $this->learningProgressService->getTrackState(Auth::user(), $track);
        $enrollment = null;
        $completedLessonIds = [];
        $lessonStates = [];
        $trackExamSet = $this->learningProgressService->getTrackExamSet($track);
        $trackExamAttempt = null;
        $allLessonsCompleted = false;

        if (Auth::check()) {
            $enrollment = TrackEnrollment::where('user_id', Auth::id())
                ->where('learning_track_id', $track->id)
                ->first();

            if ($enrollment) {
                $completedLessonIds = $this->learningProgressService->getCompletedLessonIds(Auth::user(), $track);
                $lessonStates = $this->learningProgressService->getLessonStates(Auth::user(), $track);
                $trackExamAttempt = $trackExamSet
                    ? $this->learningProgressService->getBestQuizAttempt(Auth::user(), $trackExamSet)
                    : null;
            }
        }

        $allLessonsCompleted = $track->lessons->isNotEmpty()
            && count($completedLessonIds) >= $track->lessons->count();

        return view('tracks.show', compact(
            'track',
            'trackState',
            'enrollment',
            'completedLessonIds',
            'lessonStates',
            'trackExamSet',
            'trackExamAttempt',
            'allLessonsCompleted',
        ));
    }

    public function enroll(string $slug)
    {
        $track = LearningTrack::where('slug', $slug)
            ->where('publish_status', 'published')
            ->firstOrFail();

        $user = Auth::user();
        $trackState = $this->learningProgressService->getTrackState($user, $track);

        if (!$trackState['unlocked']) {
            return redirect()->route('tracks.show', $slug)
                ->with('error', $trackState['reason']);
        }

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

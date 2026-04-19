<?php

namespace App\Http\Controllers;

use App\Models\LearningTrack;
use App\Models\TrackEnrollment;
use App\Services\GuestLearningService;
use App\Services\LearningProgressService;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function __construct(
        private LearningProgressService $learningProgressService,
        private GuestLearningService $guestLearningService,
    ) {}

    public function index()
    {
        $tracks = LearningTrack::where('publish_status', 'published')
            ->orderBy('sort_order')
            ->withCount('lessons')
            ->get();

        if (Auth::check()) {
            $trackStates = $this->learningProgressService->getTrackStates(Auth::user(), $tracks);
            $enrolledTrackIds = collect($trackStates)
                ->filter(fn ($state) => $state['enrollment'] !== null)
                ->keys()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();
        } else {
            $trackStates = $this->guestLearningService->getTrackStates($tracks);
            $enrolledTrackIds = [];
        }

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

        $user = Auth::user();
        $trackState = $user
            ? $this->learningProgressService->getTrackState($user, $track)
            : $this->guestLearningService->getTrackState($track);
        $enrollment = null;
        $completedLessonIds = [];
        $lessonStates = [];
        $trackExamSet = $this->learningProgressService->getTrackExamSet($track);
        $trackExamAttempt = null;
        $allLessonsCompleted = false;

        if ($user) {
            $enrollment = TrackEnrollment::where('user_id', $user->id)
                ->where('learning_track_id', $track->id)
                ->first();

            if ($enrollment) {
                $completedLessonIds = $this->learningProgressService->getCompletedLessonIds($user, $track);
                $lessonStates = $this->learningProgressService->getLessonStates($user, $track);
                $trackExamAttempt = $trackExamSet
                    ? $this->learningProgressService->getBestQuizAttempt($user, $trackExamSet)
                    : null;
            }
        } else {
            $completedLessonIds = $this->guestLearningService->getCompletedLessonIds($track);
            $completedLessonCodes = $this->guestLearningService->getCompletedLessonCodes($track);

            foreach ($track->lessons as $lesson) {
                $lessonStates[$lesson->id] = $this->guestLearningService->getLessonState($lesson, $completedLessonCodes);
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
            ->with('success', '트랙 학습을 시작했어요.');
    }
}

<?php

namespace App\Services;

use App\Models\LearningTrack;
use App\Models\Lesson;
use App\Models\LessonAttempt;
use App\Models\QuizAttempt;
use App\Models\QuizSet;
use App\Models\TrackEnrollment;
use App\Models\User;
use Illuminate\Support\Collection;

class LearningProgressService
{
    public function getTrackStates(?User $user, iterable $tracks): array
    {
        $tracks = $tracks instanceof Collection ? $tracks : collect($tracks);
        $tracksByCode = $tracks->keyBy('code');

        $enrollments = collect();
        if ($user) {
            $enrollments = TrackEnrollment::where('user_id', $user->id)
                ->whereIn('learning_track_id', $tracks->pluck('id'))
                ->get()
                ->keyBy('learning_track_id');
        }

        $states = [];
        foreach ($tracks as $track) {
            $states[$track->id] = $this->getTrackState($user, $track, $tracksByCode, $enrollments);
        }

        return $states;
    }

    public function getTrackState(?User $user, LearningTrack $track, ?Collection $tracksByCode = null, ?Collection $enrollments = null): array
    {
        $tracksByCode ??= LearningTrack::where('publish_status', 'published')->get()->keyBy('code');
        $enrollments ??= collect();

        $enrollment = $user
            ? ($enrollments[$track->id] ?? TrackEnrollment::where('user_id', $user->id)->where('learning_track_id', $track->id)->first())
            : null;

        $requirements = $track->unlock_rule_json['requires'] ?? [];
        foreach ($requirements as $requirement) {
            $parsed = $this->normalizeTrackRequirement($requirement);
            $requiredTrack = $tracksByCode->get($parsed['code'])
                ?? LearningTrack::where('code', $parsed['code'])->first();

            if (!$requiredTrack) {
                continue;
            }

            $requiredEnrollment = $user
                ? ($enrollments[$requiredTrack->id] ?? TrackEnrollment::where('user_id', $user->id)->where('learning_track_id', $requiredTrack->id)->first())
                : null;

            $met = match ($parsed['type']) {
                'track_exam_passed' => (bool) $requiredEnrollment?->passed_exam_at,
                default => (bool) $requiredEnrollment?->completed_at,
            };

            if (!$met) {
                return [
                    'unlocked' => false,
                    'reason' => $parsed['type'] === 'track_exam_passed'
                        ? "{$requiredTrack->title} 트랙 시험 통과 후 열립니다."
                        : "{$requiredTrack->title} 완료 후 열립니다.",
                    'enrollment' => $enrollment,
                    'completed' => (bool) $enrollment?->completed_at,
                    'passed_exam' => (bool) $enrollment?->passed_exam_at,
                ];
            }
        }

        return [
            'unlocked' => true,
            'reason' => null,
            'enrollment' => $enrollment,
            'completed' => (bool) $enrollment?->completed_at,
            'passed_exam' => (bool) $enrollment?->passed_exam_at,
        ];
    }

    public function getLessonStates(User $user, LearningTrack $track): array
    {
        $completedCodes = $this->getCompletedLessonCodes($user, $track);
        $states = [];

        foreach ($track->lessons as $lesson) {
            $states[$lesson->id] = $this->getLessonState($user, $lesson, $completedCodes);
        }

        return $states;
    }

    public function getLessonState(User $user, Lesson $lesson, ?array $completedCodes = null): array
    {
        $completedCodes ??= $this->getCompletedLessonCodes($user, $lesson->learningTrack);
        $requirements = $lesson->unlock_rule_json['requires'] ?? [];

        foreach ($requirements as $requiredCode) {
            if (!in_array($requiredCode, $completedCodes, true)) {
                return [
                    'unlocked' => false,
                    'reason' => '선행 레슨을 먼저 완료해야 합니다.',
                ];
            }
        }

        return [
            'unlocked' => true,
            'reason' => null,
        ];
    }

    public function getCompletedLessonIds(User $user, LearningTrack $track): array
    {
        return LessonAttempt::where('user_id', $user->id)
            ->whereIn('lesson_id', $track->lessons->pluck('id'))
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->all();
    }

    public function getCompletedLessonCodes(User $user, LearningTrack $track): array
    {
        return Lesson::whereIn('id', $this->getCompletedLessonIds($user, $track))
            ->pluck('code')
            ->all();
    }

    public function getTrackExamSet(LearningTrack $track): ?QuizSet
    {
        return $track->quizSets()
            ->where('scope_type', 'track')
            ->where('publish_status', 'published')
            ->orderByDesc('id')
            ->first();
    }

    public function getBestQuizAttempt(User $user, QuizSet $quizSet): ?QuizAttempt
    {
        return QuizAttempt::where('user_id', $user->id)
            ->where('quiz_set_id', $quizSet->id)
            ->orderByDesc('score_percentage')
            ->latest('id')
            ->first();
    }

    public function hasPassedQuizSet(User $user, QuizSet $quizSet): bool
    {
        return QuizAttempt::where('user_id', $user->id)
            ->where('quiz_set_id', $quizSet->id)
            ->where('passed', true)
            ->exists();
    }

    public function markTrackExamPassed(User $user, LearningTrack $track, int $percentage): TrackEnrollment
    {
        $enrollment = TrackEnrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'learning_track_id' => $track->id,
            ],
            [
                'status' => 'active',
                'progress_percent' => 0,
                'started_at' => now(),
                'last_accessed_at' => now(),
            ]
        );

        $bestScore = max((int) ($enrollment->track_exam_best_score ?? 0), $percentage);

        $enrollment->update([
            'track_exam_best_score' => $bestScore,
            'passed_exam_at' => $enrollment->passed_exam_at ?? now(),
            'last_accessed_at' => now(),
        ]);

        return $this->syncTrackEnrollment($user, $track) ?? $enrollment->fresh();
    }

    public function syncTrackEnrollment(User $user, LearningTrack $track): ?TrackEnrollment
    {
        $enrollment = TrackEnrollment::where('user_id', $user->id)
            ->where('learning_track_id', $track->id)
            ->first();

        if (!$enrollment) {
            return null;
        }

        $lessonIds = $track->lessons()->where('publish_status', 'published')->pluck('id');
        $totalLessons = $lessonIds->count();
        $completedLessons = LessonAttempt::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();

        $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
        $trackExamSet = $this->getTrackExamSet($track);
        $requiresExam = $trackExamSet !== null;
        $examPassed = !$requiresExam || (bool) $enrollment->passed_exam_at;
        $isCompleted = $progress >= 100 && $examPassed;

        $enrollment->update([
            'progress_percent' => $progress,
            'status' => $isCompleted ? 'completed' : 'active',
            'completed_at' => $isCompleted ? ($enrollment->completed_at ?? now()) : null,
            'last_accessed_at' => now(),
        ]);

        return $enrollment->fresh();
    }

    private function normalizeTrackRequirement(array|string $requirement): array
    {
        if (is_string($requirement)) {
            return [
                'type' => 'track_completed',
                'code' => $requirement,
            ];
        }

        return [
            'type' => $requirement['type'] ?? 'track_completed',
            'code' => $requirement['code'] ?? '',
        ];
    }
}

<?php

namespace App\Services;

use App\Models\LearningTrack;
use App\Models\Lesson;
use App\Models\QuizSet;
use Illuminate\Support\Collection;

class GuestLearningService
{
    private const SESSION_KEY = 'guest_learning';

    public function getTrackStates(iterable $tracks): array
    {
        $tracks = $tracks instanceof Collection ? $tracks : collect($tracks);
        $tracksByCode = $tracks->keyBy('code');
        $states = [];

        foreach ($tracks as $track) {
            $states[$track->id] = $this->getTrackState($track, $tracksByCode);
        }

        return $states;
    }

    public function getTrackState(LearningTrack $track, ?Collection $tracksByCode = null): array
    {
        $tracksByCode ??= LearningTrack::where('publish_status', 'published')->get()->keyBy('code');
        $requirements = $track->unlock_rule_json['requires'] ?? [];

        foreach ($requirements as $requirement) {
            $parsed = $this->normalizeTrackRequirement($requirement);
            $requiredTrack = $tracksByCode->get($parsed['code'])
                ?? LearningTrack::where('code', $parsed['code'])->first();

            if (!$requiredTrack) {
                continue;
            }

            $met = match ($parsed['type']) {
                'track_exam_passed' => $this->hasPassedTrackExam($requiredTrack),
                default => $this->isTrackCompleted($requiredTrack),
            };

            if (!$met) {
                return [
                    'unlocked' => false,
                    'reason' => $parsed['type'] === 'track_exam_passed'
                        ? "{$requiredTrack->title} 트랙 시험을 먼저 통과해야 해요."
                        : "{$requiredTrack->title} 트랙을 먼저 완료해야 해요.",
                    'enrollment' => null,
                    'completed' => $this->isTrackCompleted($track),
                    'passed_exam' => $this->hasPassedTrackExam($track),
                ];
            }
        }

        return [
            'unlocked' => true,
            'reason' => null,
            'enrollment' => null,
            'completed' => $this->isTrackCompleted($track),
            'passed_exam' => $this->hasPassedTrackExam($track),
        ];
    }

    public function getLessonState(Lesson $lesson, ?array $completedCodes = null): array
    {
        $completedCodes ??= $this->getCompletedLessonCodes($lesson->learningTrack);
        $requirements = $lesson->unlock_rule_json['requires'] ?? [];

        foreach ($requirements as $requiredCode) {
            if (!in_array($requiredCode, $completedCodes, true)) {
                return [
                    'unlocked' => false,
                    'reason' => '이전 레슨을 먼저 완료하면 열려요.',
                ];
            }
        }

        return [
            'unlocked' => true,
            'reason' => null,
        ];
    }

    public function getCompletedLessonCodes(?LearningTrack $track = null): array
    {
        $codes = $this->data()['completed_lesson_codes'] ?? [];
        $codes = array_values(array_unique(array_filter($codes, fn ($code) => is_string($code) && $code !== '')));

        if (!$track) {
            return $codes;
        }

        $trackLessonCodes = $track->lessons()->pluck('code')->all();

        return array_values(array_intersect($codes, $trackLessonCodes));
    }

    public function getCompletedLessonIds(LearningTrack $track): array
    {
        $codes = $this->getCompletedLessonCodes($track);

        if ($codes === []) {
            return [];
        }

        return Lesson::whereIn('code', $codes)->pluck('id')->all();
    }

    public function completeLesson(Lesson $lesson): void
    {
        $data = $this->data();
        $completedLessonCodes = $data['completed_lesson_codes'] ?? [];
        $completedLessonCodes[] = $lesson->code;
        $data['completed_lesson_codes'] = array_values(array_unique($completedLessonCodes));

        $this->storeData($data);
    }

    public function getQuizAttemptSummary(string|QuizSet $quizSet): ?array
    {
        $code = $quizSet instanceof QuizSet ? $quizSet->code : $quizSet;

        return $this->data()['quiz_attempts'][$code] ?? null;
    }

    public function hasPassedQuizSet(QuizSet $quizSet): bool
    {
        return (bool) ($this->getQuizAttemptSummary($quizSet)['passed'] ?? false);
    }

    public function hasPassedTrackExam(LearningTrack $track): bool
    {
        return in_array($track->code, $this->data()['passed_track_exam_codes'] ?? [], true);
    }

    public function isTrackCompleted(LearningTrack $track): bool
    {
        $publishedLessonCodes = $track->lessons()
            ->where('publish_status', 'published')
            ->pluck('code')
            ->all();

        if ($publishedLessonCodes === []) {
            return false;
        }

        $completedLessonCodes = $this->getCompletedLessonCodes();
        $hasCompletedLessons = count(array_intersect($publishedLessonCodes, $completedLessonCodes)) === count($publishedLessonCodes);
        $trackExamSet = $track->quizSets()
            ->where('scope_type', 'track')
            ->where('publish_status', 'published')
            ->orderByDesc('id')
            ->first();

        if (!$hasCompletedLessons) {
            return false;
        }

        return !$trackExamSet || $this->hasPassedTrackExam($track);
    }

    public function storeQuizAttempt(QuizSet $quizSet, array $score, array $weakPoints = []): array
    {
        $data = $this->data();
        $quizAttempts = $data['quiz_attempts'] ?? [];

        $summary = [
            'quiz_set_id' => $quizSet->id,
            'quiz_set_code' => $quizSet->code,
            'lesson_id' => $quizSet->lesson_id,
            'learning_track_id' => $quizSet->learning_track_id,
            'score_percentage' => $score['percentage'],
            'earned_points' => $score['earned_points'],
            'total_points' => $score['total_points'],
            'total_items' => $score['total_items'],
            'correct_count' => $score['correct_count'],
            'passed' => $score['percentage'] >= $quizSet->pass_score,
            'weak_points_json' => $weakPoints,
            'submitted_at' => now()->toIso8601String(),
        ];

        $quizAttempts[$quizSet->code] = $summary;
        $data['quiz_attempts'] = $quizAttempts;

        if ($quizSet->scope_type === 'track' && $summary['passed'] && $quizSet->learningTrack) {
            $passedTrackExamCodes = $data['passed_track_exam_codes'] ?? [];
            $passedTrackExamCodes[] = $quizSet->learningTrack->code;
            $data['passed_track_exam_codes'] = array_values(array_unique($passedTrackExamCodes));
        }

        $this->storeData($data);

        return $summary;
    }

    private function data(): array
    {
        return session(self::SESSION_KEY, []);
    }

    private function storeData(array $data): void
    {
        session([self::SESSION_KEY => $data]);
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

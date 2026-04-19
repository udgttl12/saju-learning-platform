<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuizItem;
use App\Models\QuizItemAttempt;
use App\Models\QuizSet;
use App\Models\User;

class QuizService
{
    public function getQuizSetByCode(string $code): QuizSet
    {
        return QuizSet::where('code', $code)
            ->where('publish_status', 'published')
            ->with(['items', 'lesson', 'learningTrack'])
            ->firstOrFail();
    }

    public function gradeSubmission(QuizSet $quizSet, array $answers): array
    {
        $results = [];

        foreach ($quizSet->items as $item) {
            $userAnswer = $answers[$item->id] ?? null;
            $correct = $this->gradeItem($item, $userAnswer);

            $results[] = [
                'item_id' => $item->id,
                'question_type' => $item->question_type,
                'prompt_text' => $item->prompt_text,
                'user_answer' => $userAnswer,
                'correct' => $correct,
                'explanation' => $item->explanation_text,
                'points' => $item->points,
                'earned' => $correct ? $item->points : 0,
                'source_type' => $item->source_type,
                'target_hanja_char_id' => $item->target_hanja_char_id,
                'concept_key' => $item->concept_key,
                'meta_json' => $item->meta_json ?? [],
                'correct_answer' => $this->extractCorrectAnswerLabel($item),
            ];
        }

        return $results;
    }

    public function calculateScore(array $results): array
    {
        $totalPoints = array_sum(array_column($results, 'points'));
        $earnedPoints = array_sum(array_column($results, 'earned'));
        $percentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;

        return [
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'percentage' => $percentage,
            'total_items' => count($results),
            'correct_count' => count(array_filter($results, fn($r) => $r['correct'])),
        ];
    }

    public function recordAttempt(User $user, QuizSet $quizSet, array $results, array $score, array $answers): QuizAttempt
    {
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_set_id' => $quizSet->id,
            'score_percentage' => $score['percentage'],
            'earned_points' => $score['earned_points'],
            'total_points' => $score['total_points'],
            'total_items' => $score['total_items'],
            'correct_count' => $score['correct_count'],
            'passed' => $score['percentage'] >= $quizSet->pass_score,
            'weak_points_json' => $this->summarizeWeakPoints($results),
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        foreach ($results as $result) {
            QuizItemAttempt::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_item_id' => $result['item_id'],
                'question_snapshot_json' => [
                    'prompt_text' => $result['prompt_text'],
                    'question_type' => $result['question_type'],
                    'concept_key' => $result['concept_key'],
                    'correct_answer' => $result['correct_answer'],
                    'explanation' => $result['explanation'],
                ],
                'user_answer_json' => [
                    'raw' => $answers[$result['item_id']] ?? null,
                ],
                'is_correct' => $result['correct'],
                'earned_points' => $result['earned'],
                'elapsed_ms' => 0,
            ]);
        }

        return $attempt->load('itemAttempts');
    }

    public function summarizeWeakPoints(array $results): array
    {
        $weakPoints = [];

        foreach ($results as $result) {
            if ($result['correct']) {
                continue;
            }

            $key = $result['concept_key'] ?: "question_type:{$result['question_type']}";
            $label = $result['meta_json']['review_title']
                ?? $result['meta_json']['weak_label']
                ?? $result['concept_key']
                ?? match ($result['question_type']) {
                    'multiple_choice' => '객관식 개념 확인',
                    'true_false' => 'OX 개념 확인',
                    'short_answer' => '단답형 인출',
                    'self_check' => '자기설명형 점검',
                    default => '개념 확인',
                };

            if (!isset($weakPoints[$key])) {
                $weakPoints[$key] = [
                    'key' => $key,
                    'label' => $label,
                    'count' => 0,
                    'review_lesson_code' => $result['meta_json']['review_lesson_code'] ?? null,
                ];
            }

            $weakPoints[$key]['count']++;
        }

        return array_values($weakPoints);
    }

    private function gradeItem(QuizItem $item, mixed $userAnswer): bool
    {
        if ($userAnswer === null) {
            return false;
        }

        $payload = $item->answer_payload_json ?? [];

        return match ($item->question_type) {
            'multiple_choice' => $this->gradeMultipleChoice($payload, $userAnswer),
            'true_false' => $this->gradeTrueFalse($payload, $userAnswer),
            'short_answer' => $this->gradeShortAnswer($payload, $userAnswer),
            'self_check' => true,
            default => false,
        };
    }

    private function gradeMultipleChoice(array $payload, mixed $userAnswer): bool
    {
        $correctIndex = $payload['correct_choice_index'] ?? null;
        return $correctIndex !== null && (int) $userAnswer === (int) $correctIndex;
    }

    private function gradeTrueFalse(array $payload, mixed $userAnswer): bool
    {
        $correctBoolean = $payload['correct_boolean'] ?? null;
        if ($correctBoolean === null) {
            return false;
        }
        return (bool) $correctBoolean === filter_var($userAnswer, FILTER_VALIDATE_BOOLEAN);
    }

    private function gradeShortAnswer(array $payload, mixed $userAnswer): bool
    {
        $acceptedAnswers = $payload['accepted_answers'] ?? [$payload['correct_answer'] ?? ''];
        $normalizedUserAnswer = $this->normalizeAnswer($userAnswer);

        foreach ($acceptedAnswers as $answer) {
            if ($normalizedUserAnswer === $this->normalizeAnswer($answer)) {
                return true;
            }
        }

        return false;
    }

    private function extractCorrectAnswerLabel(QuizItem $item): ?string
    {
        $payload = $item->answer_payload_json ?? [];

        return match ($item->question_type) {
            'multiple_choice' => isset($item->choices_json[$payload['correct_choice_index'] ?? -1])
                ? $item->choices_json[$payload['correct_choice_index']]
                : null,
            'true_false' => ($payload['correct_boolean'] ?? false) ? 'O (맞다)' : 'X (틀리다)',
            'short_answer' => implode(', ', $payload['accepted_answers'] ?? array_filter([$payload['correct_answer'] ?? null])),
            'self_check' => $payload['check_label'] ?? '자기 점검 완료',
            default => null,
        };
    }

    private function normalizeAnswer(mixed $value): string
    {
        $string = is_scalar($value) ? (string) $value : '';
        $string = mb_strtolower(trim($string));

        return preg_replace('/\s+/u', '', $string) ?? $string;
    }
}

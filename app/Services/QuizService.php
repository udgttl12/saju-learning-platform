<?php

namespace App\Services;

use App\Models\QuizSet;

class QuizService
{
    public function getQuizSetByCode(string $code): QuizSet
    {
        return QuizSet::where('code', $code)
            ->where('publish_status', 'published')
            ->with('items')
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

    private function gradeItem($item, $userAnswer): bool
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

    private function gradeMultipleChoice(array $payload, $userAnswer): bool
    {
        $correctIndex = $payload['correct_choice_index'] ?? null;
        return $correctIndex !== null && (int) $userAnswer === (int) $correctIndex;
    }

    private function gradeTrueFalse(array $payload, $userAnswer): bool
    {
        $correctBoolean = $payload['correct_boolean'] ?? null;
        if ($correctBoolean === null) {
            return false;
        }
        return (bool) $correctBoolean === filter_var($userAnswer, FILTER_VALIDATE_BOOLEAN);
    }

    private function gradeShortAnswer(array $payload, $userAnswer): bool
    {
        $correctAnswer = $payload['correct_answer'] ?? '';
        return mb_strtolower(trim($userAnswer)) === mb_strtolower(trim($correctAnswer));
    }
}

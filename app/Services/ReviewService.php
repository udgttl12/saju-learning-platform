<?php

namespace App\Services;

use App\Models\QuizSet;
use App\Models\ReviewCard;
use App\Models\ReviewLog;
use App\Models\User;
use Carbon\Carbon;

class ReviewService
{
    public function getDueCards(User $user)
    {
        return ReviewCard::where('user_id', $user->id)
            ->where('due_at', '<=', Carbon::now())
            ->with('hanjaChar')
            ->orderBy('due_at')
            ->get();
    }

    public function createFromQuizResult(User $user, array $results, QuizSet $quizSet): int
    {
        $created = 0;

        foreach ($results as $result) {
            if ($result['correct'] || empty($result['target_hanja_char_id'])) {
                continue;
            }

            $card = ReviewCard::where('user_id', $user->id)
                ->where('hanja_char_id', $result['target_hanja_char_id'])
                ->first();

            if ($card) {
                $card->update([
                    'due_at' => Carbon::now(),
                    'stage' => $card->stage === 'mastered' ? 'lapsed' : $card->stage,
                ]);
            } else {
                ReviewCard::create([
                    'user_id' => $user->id,
                    'hanja_char_id' => $result['target_hanja_char_id'],
                    'source_type' => 'quiz',
                    'source_id' => $quizSet->id,
                    'stage' => 'new',
                    'ease_factor' => 2.50,
                    'interval_days' => 0,
                    'repetitions' => 0,
                    'due_at' => Carbon::now(),
                ]);
                $created++;
            }
        }

        return $created;
    }

    public function processAnswer(ReviewCard $card, string $result): ReviewCard
    {
        $beforeState = [
            'stage' => $card->stage,
            'ease_factor' => (float) $card->ease_factor,
            'interval_days' => $card->interval_days,
            'repetitions' => $card->repetitions,
        ];

        $easeFactor = (float) $card->ease_factor;
        $interval = $card->interval_days;
        $repetitions = $card->repetitions;

        switch ($result) {
            case 'again':
                $interval = 0;
                $card->stage = 'learning';
                $easeFactor -= 0.20;
                $repetitions = 0;
                break;

            case 'hard':
                $interval = max(1, (int) round($interval * 1.2));
                $easeFactor -= 0.15;
                $repetitions++;
                break;

            case 'good':
                if ($interval === 0) {
                    $interval = 1;
                } else {
                    $interval = max(1, (int) round($interval * $easeFactor));
                }
                $repetitions++;
                $card->stage = 'review';
                break;

            case 'easy':
                if ($interval === 0) {
                    $interval = 4;
                } else {
                    $interval = max(1, (int) round($interval * $easeFactor * 1.3));
                }
                $easeFactor += 0.15;
                $repetitions++;
                $card->stage = 'review';
                break;
        }

        $easeFactor = max(1.30, $easeFactor);

        $card->ease_factor = $easeFactor;
        $card->interval_days = $interval;
        $card->repetitions = $repetitions;
        $card->due_at = Carbon::now()->addDays($interval);
        $card->last_result = $result;
        $card->last_reviewed_at = Carbon::now();
        $card->save();

        ReviewLog::create([
            'review_card_id' => $card->id,
            'user_id' => $card->user_id,
            'reviewed_at' => Carbon::now(),
            'result' => $result,
            'response_ms' => 0,
            'score' => $result === 'again' ? 0 : ($result === 'hard' ? 0.5 : ($result === 'good' ? 0.8 : 1.0)),
            'before_state_json' => $beforeState,
            'after_state_json' => [
                'stage' => $card->stage,
                'ease_factor' => (float) $card->ease_factor,
                'interval_days' => $card->interval_days,
                'repetitions' => $card->repetitions,
            ],
            'created_at' => Carbon::now(),
        ]);

        return $card;
    }
}

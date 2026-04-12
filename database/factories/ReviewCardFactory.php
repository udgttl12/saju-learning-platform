<?php

namespace Database\Factories;

use App\Models\HanjaChar;
use App\Models\ReviewCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReviewCard>
 */
class ReviewCardFactory extends Factory
{
    protected $model = ReviewCard::class;

    public function definition(): array
    {
        $stage = fake()->randomElement(['new', 'learning', 'reviewing', 'lapsed', 'mastered']);

        return [
            'user_id' => User::factory(),
            'hanja_char_id' => HanjaChar::factory(),
            'source_type' => fake()->randomElement(['lesson', 'quiz', 'practice', 'manual']),
            'source_id' => fake()->optional()->numberBetween(1, 50),
            'stage' => $stage,
            'ease_factor' => fake()->randomFloat(2, 1.30, 2.80),
            'interval_days' => fake()->numberBetween(0, 30),
            'repetitions' => fake()->numberBetween(0, 12),
            'due_at' => fake()->optional(0.9)->dateTimeBetween('-2 days', '+14 days'),
            'last_result' => fake()->optional(0.7)->randomElement(['again', 'hard', 'good', 'easy']),
            'last_reviewed_at' => fake()->optional(0.7)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}

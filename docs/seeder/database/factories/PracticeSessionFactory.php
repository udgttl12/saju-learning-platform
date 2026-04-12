<?php

namespace Database\Factories;

use App\Models\HanjaChar;
use App\Models\Lesson;
use App\Models\PracticeSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PracticeSession>
 */
class PracticeSessionFactory extends Factory
{
    protected $model = PracticeSession::class;

    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-30 days', 'now');
        $durationMs = fake()->numberBetween(15_000, 240_000);
        $endedAt = (clone $startedAt)->modify('+' . (int) floor($durationMs / 1000) . ' seconds');

        return [
            'user_id' => User::factory(),
            'hanja_char_id' => HanjaChar::factory(),
            'lesson_id' => Lesson::factory(),
            'practice_mode' => fake()->randomElement(['trace', 'overlay', 'free']),
            'input_device' => fake()->randomElement(['mouse', 'touch', 'pen', 'unknown']),
            'status' => fake()->randomElement(['in_progress', 'completed', 'abandoned']),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_ms' => $durationMs,
            'self_rating' => fake()->optional(0.8)->numberBetween(1, 5),
            'session_meta_json' => [
                'browser' => fake()->randomElement(['chrome', 'edge', 'safari']),
                'canvas_scale' => fake()->randomElement([1, 1.5, 2]),
                'device_pixel_ratio' => fake()->randomFloat(1, 1, 3),
            ],
        ];
    }
}

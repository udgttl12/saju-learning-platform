<?php

namespace Database\Factories;

use App\Models\LearningTrack;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LearningTrack>
 */
class LearningTrackFactory extends Factory
{
    protected $model = LearningTrack::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'code' => 'TRK_' . strtoupper(fake()->unique()->bothify('??###')),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(10, 999),
            'title' => Str::title($title),
            'short_description' => fake()->sentence(),
            'target_audience' => 'adult_hobby_beginner',
            'difficulty_level' => fake()->numberBetween(1, 3),
            'estimated_total_minutes' => fake()->numberBetween(40, 240),
            'sort_order' => fake()->numberBetween(1, 50),
            'publish_status' => 'published',
            'published_at' => now(),
        ];
    }
}

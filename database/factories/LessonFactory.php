<?php

namespace Database\Factories;

use App\Models\LearningTrack;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(4, true);

        return [
            'learning_track_id' => LearningTrack::factory(),
            'code' => 'LES_' . strtoupper(fake()->unique()->bothify('??###')),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(10, 999),
            'title' => Str::title($title),
            'objective' => fake()->sentence(12),
            'summary' => fake()->sentence(),
            'lesson_type' => fake()->randomElement(['concept', 'hanja_card', 'practice', 'quiz', 'example_chart']),
            'difficulty_level' => fake()->numberBetween(1, 3),
            'estimated_minutes' => fake()->numberBetween(5, 25),
            'unlock_rule_json' => [
                'type' => 'sequential',
                'requires_previous_completion' => fake()->boolean(70),
            ],
            'sort_order' => fake()->numberBetween(1, 50),
            'publish_status' => 'published',
            'published_at' => now(),
        ];
    }
}

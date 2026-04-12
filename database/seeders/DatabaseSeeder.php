<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LearningTrackSeeder::class,
            LessonSeeder::class,
            HanjaGroupSeeder::class,
            HanjaCharSeeder::class,
            QuizSeeder::class,
            SajuExampleSeeder::class,
            LessonContentEnrichSeeder::class,
            SajuStructureTrackSeeder::class,
            DemoProgressSeeder::class,
        ]);
    }
}

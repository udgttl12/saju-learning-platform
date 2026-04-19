<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 콘텐츠 테이블 초기화 (사용자 데이터는 보존)
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('admin_audit_logs')->truncate();
        DB::table('bookmarks')->truncate();
        DB::table('review_logs')->truncate();
        DB::table('review_cards')->truncate();
        DB::table('lesson_attempts')->truncate();
        DB::table('quiz_item_attempts')->truncate();
        DB::table('quiz_attempts')->truncate();
        DB::table('quiz_items')->truncate();
        DB::table('quiz_sets')->truncate();
        DB::table('practice_strokes')->truncate();
        DB::table('practice_sessions')->truncate();
        DB::table('stroke_templates')->truncate();
        DB::table('lesson_hanja_links')->truncate();
        DB::table('hanja_group_links')->truncate();
        DB::table('hanja_chars')->truncate();
        DB::table('hanja_groups')->truncate();
        DB::table('lesson_steps')->truncate();
        DB::table('lessons')->truncate();
        DB::table('track_enrollments')->truncate();
        DB::table('learning_tracks')->truncate();
        DB::table('saju_examples')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->call([
            UserSeeder::class,
            LearningTrackSeeder::class,
            LessonSeeder::class,
            HanjaGroupSeeder::class,
            HanjaCharSeeder::class,
            QuizSeeder::class,
            SajuExampleSeeder::class,
            LessonContentEnrichSeeder::class,
            EarthBranchAdvancedTrackSeeder::class,
            SajuStructureTrackSeeder::class,
            MyeongrihakBasicsTrackSeeder::class,
            TwelveShinsalTrackSeeder::class,
            YukchinlonTrackSeeder::class,
            ExamQuizSeeder::class,
            ManselyeokEnrichSeeder::class,
            DemoProgressSeeder::class,
        ]);
    }
}

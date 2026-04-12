<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearningTrackSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('learning_tracks')->insert([
            [
                'id' => 1,
                'code' => 'TRACK_PREP',
                'slug' => 'hanja-prep',
                'title' => '한자 준비운동',
                'short_description' => '획과 구조를 두려워하지 않도록 몸풀기부터 시작하는 트랙',
                'target_audience' => 'adult_hobby_beginner',
                'difficulty_level' => 1,
                'estimated_total_minutes' => 40,
                'sort_order' => 1,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'code' => 'TRACK_FIVE_ELEMENTS',
                'slug' => 'five-elements',
                'title' => '오행 한자',
                'short_description' => '목·화·토·금·수 5글자를 먼저 익히는 핵심 트랙',
                'target_audience' => 'adult_hobby_beginner',
                'difficulty_level' => 1,
                'estimated_total_minutes' => 35,
                'sort_order' => 2,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'code' => 'TRACK_HEAVENLY_STEMS',
                'slug' => 'heavenly-stems',
                'title' => '천간 한자',
                'short_description' => '갑을병정무기경신임계 10글자를 읽고 구분하는 트랙',
                'target_audience' => 'adult_hobby_beginner',
                'difficulty_level' => 2,
                'estimated_total_minutes' => 80,
                'sort_order' => 3,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'code' => 'TRACK_EARTHLY_BRANCHES',
                'slug' => 'earthly-branches',
                'title' => '지지 한자',
                'short_description' => '자축인묘진사오미신유술해 12글자를 읽는 트랙',
                'target_audience' => 'adult_hobby_beginner',
                'difficulty_level' => 2,
                'estimated_total_minutes' => 90,
                'sort_order' => 4,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'code' => 'TRACK_CHART_READING',
                'slug' => 'chart-reading',
                'title' => '만세력 첫 읽기',
                'short_description' => '연주·월주·일주·시주를 눈으로 읽는 첫 실전 트랙',
                'target_audience' => 'adult_hobby_beginner',
                'difficulty_level' => 2,
                'estimated_total_minutes' => 50,
                'sort_order' => 5,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('track_enrollments')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'learning_track_id' => 1,
                'status' => 'active',
                'progress_percent' => 50.00,
                'started_at' => now(),
                'last_accessed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'learning_track_id' => 2,
                'status' => 'active',
                'progress_percent' => 20.00,
                'started_at' => now(),
                'last_accessed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

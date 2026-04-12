<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DemoProgressSeeder extends Seeder
{
    public function run(): void
    {
        // lesson_attempts: 2건
        DB::table('lesson_attempts')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'lesson_id' => 1,
                'status' => 'completed',
                'progress_percent' => 100.00,
                'latest_score' => 95.00,
                'best_score' => 95.00,
                'total_time_seconds' => 780,
                'first_started_at' => now(),
                'last_accessed_at' => now(),
                'completed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'lesson_id' => 3,
                'status' => 'in_progress',
                'progress_percent' => 45.00,
                'latest_score' => 70.00,
                'best_score' => 70.00,
                'total_time_seconds' => 420,
                'first_started_at' => now(),
                'last_accessed_at' => now(),
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // review_cards: 3건
        DB::table('review_cards')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'hanja_char_id' => 1,
                'source_type' => 'lesson',
                'source_id' => 3,
                'stage' => 'learning',
                'ease_factor' => 2.40,
                'interval_days' => 1,
                'repetitions' => 1,
                'due_at' => Carbon::now()->addDay(),
                'last_result' => 'good',
                'last_reviewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'hanja_char_id' => 6,
                'source_type' => 'lesson',
                'source_id' => 4,
                'stage' => 'new',
                'ease_factor' => 2.50,
                'interval_days' => 0,
                'repetitions' => 0,
                'due_at' => now(),
                'last_result' => null,
                'last_reviewed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'hanja_char_id' => 16,
                'source_type' => 'lesson',
                'source_id' => 5,
                'stage' => 'new',
                'ease_factor' => 2.50,
                'interval_days' => 0,
                'repetitions' => 0,
                'due_at' => now(),
                'last_result' => null,
                'last_reviewed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // review_logs: 1건
        DB::table('review_logs')->insert([
            [
                'id' => 1,
                'review_card_id' => 1,
                'user_id' => 2,
                'reviewed_at' => now(),
                'result' => 'good',
                'response_ms' => 3200,
                'score' => 90.00,
                'before_state_json' => json_encode(['stage' => 'new', 'interval_days' => 0]),
                'after_state_json' => json_encode(['stage' => 'learning', 'interval_days' => 1]),
                'created_at' => now(),
            ],
        ]);

        // bookmarks: 2건
        DB::table('bookmarks')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'target_type' => 'lesson',
                'target_id' => 3,
                'note' => '오행은 자주 다시 보려고 북마크',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'target_type' => 'hanja_char',
                'target_id' => 6,
                'note' => '甲이 자꾸 헷갈려서 체크',
                'created_at' => now(),
            ],
        ]);

        // admin_audit_logs: 1건
        DB::table('admin_audit_logs')->insert([
            [
                'id' => 1,
                'admin_user_id' => 1,
                'entity_type' => 'seed',
                'entity_id' => null,
                'action_type' => 'initial_seed',
                'diff_json' => json_encode(['version' => 'mvp_mysql8_v1', 'notes' => '기초 트랙/한자/퀴즈/샘플 차트 seed 입력']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'seed-script',
                'created_at' => now(),
            ],
        ]);
    }
}

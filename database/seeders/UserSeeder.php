<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'email' => 'admin@example.com',
                'password' => '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'email' => 'demo@example.com',
                'password' => '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'member',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('profiles')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'display_name' => '운영 관리자',
                'beginner_level' => 'returning',
                'hanja_level' => 'intermediate',
                'daily_goal_minutes' => 20,
                'preferred_learning_style' => 'balanced',
                'timezone' => 'Asia/Seoul',
                'onboarding_completed_at' => now(),
                'memo' => '콘텐츠/운영 관리자 seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'display_name' => '사주 입문러',
                'beginner_level' => 'absolute_beginner',
                'hanja_level' => 'none',
                'daily_goal_minutes' => 15,
                'preferred_learning_style' => 'writing',
                'timezone' => 'Asia/Seoul',
                'onboarding_completed_at' => now(),
                'memo' => '성인 취미 학습자 데모 계정',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

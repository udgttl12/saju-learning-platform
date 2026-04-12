<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SajuExampleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('saju_examples')->insert([
            [
                'id' => 1,
                'code' => 'EXAMPLE_CHART_001',
                'slug' => 'spring-wood-fire-sample',
                'title' => '샘플 A | 목화 흐름 읽기',
                'description' => '천간과 지지를 처음 읽는 학습자를 위한 샘플 차트. 목과 화가 보이는 구조를 중심으로 본다.',
                'gender' => 'unknown',
                'solar_birth_datetime' => null,
                'lunar_birth_label' => null,
                'year_stem' => '甲',
                'year_branch' => '子',
                'month_stem' => '丙',
                'month_branch' => '寅',
                'day_stem' => '乙',
                'day_branch' => '巳',
                'hour_stem' => '庚',
                'hour_branch' => '午',
                'chart_json' => json_encode(['focus' => ['연주/월주/일주/시주 위치 읽기', '오행 색상 인식', '천간과 지지 분리 보기']]),
                'difficulty_level' => 1,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'code' => 'EXAMPLE_CHART_002',
                'slug' => 'metal-water-sample',
                'title' => '샘플 B | 금수 흐름 읽기',
                'description' => '가을/겨울 감각이 들어간 금수 중심 예시 차트.',
                'gender' => 'unknown',
                'solar_birth_datetime' => null,
                'lunar_birth_label' => null,
                'year_stem' => '辛',
                'year_branch' => '酉',
                'month_stem' => '癸',
                'month_branch' => '亥',
                'day_stem' => '壬',
                'day_branch' => '辰',
                'hour_stem' => '丁',
                'hour_branch' => '未',
                'chart_json' => json_encode(['focus' => ['금과 수의 연결', '지지 위치 읽기', '차트 비교 관찰']]),
                'difficulty_level' => 2,
                'publish_status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

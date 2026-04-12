<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HanjaGroupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hanja_groups')->insert([
            [
                'id' => 1,
                'group_type' => 'category',
                'code' => 'FIVE_ELEMENTS',
                'name' => '오행',
                'description' => '목·화·토·금·수 분류',
                'sort_order' => 1,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'group_type' => 'category',
                'code' => 'HEAVENLY_STEMS',
                'name' => '천간',
                'description' => '갑을병정무기경신임계',
                'sort_order' => 2,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'group_type' => 'category',
                'code' => 'EARTHLY_BRANCHES',
                'name' => '지지',
                'description' => '자축인묘진사오미신유술해',
                'sort_order' => 3,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'group_type' => 'collection',
                'code' => 'BEGINNER_CORE',
                'name' => '입문 핵심 카드',
                'description' => '처음 보는 학습자가 반드시 익혀야 할 핵심 카드',
                'sort_order' => 4,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'group_type' => 'collection',
                'code' => 'CHART_READING',
                'name' => '만세력 읽기용 카드',
                'description' => '실전관에서 바로 쓰는 카드',
                'sort_order' => 5,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

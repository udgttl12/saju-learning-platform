<?php

namespace Database\Factories;

use App\Models\HanjaChar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HanjaChar>
 */
class HanjaCharFactory extends Factory
{
    protected $model = HanjaChar::class;

    public function definition(): array
    {
        $char = fake()->unique()->randomElement(['木', '火', '土', '金', '水', '甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸', '子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥']);
        $category = match ($char) {
            '木', '火', '土', '金', '水' => 'five_elements',
            '甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸' => 'heavenly_stems',
            default => 'earthly_branches',
        };
        $element = match ($char) {
            '木', '甲', '乙', '寅', '卯' => 'wood',
            '火', '丙', '丁', '巳', '午' => 'fire',
            '土', '戊', '己', '辰', '戌', '丑', '未' => 'earth',
            '金', '庚', '辛', '申', '酉' => 'metal',
            default => 'water',
        };
        $yinYang = in_array($char, ['甲', '丙', '戊', '庚', '壬', '子', '寅', '辰', '午', '申', '戌'], true) ? 'yang' : 'yin';

        return [
            'char_value' => $char,
            'slug' => fake()->unique()->slug(2) . '-' . bin2hex(random_bytes(2)),
            'reading_ko' => fake()->randomElement(['갑', '을', '병', '정', '무', '기', '경', '신', '임', '계', '자', '축', '인', '묘', '진', '사', '오', '미', '신', '유', '술', '해', '목', '화', '토', '금', '수']),
            'meaning_ko' => fake()->randomElement(['시작', '성장', '확장', '정리', '흐름', '계절의 흐름', '사주 입문 핵심 글자']),
            'category' => $category,
            'element' => $element,
            'yin_yang' => $yinYang,
            'structure_note' => fake()->optional()->words(3, true),
            'mnemonic_text' => fake()->sentence(),
            'usage_in_saju' => fake()->sentence(12),
            'stroke_count' => fake()->numberBetween(1, 12),
            'is_core' => true,
            'publish_status' => 'published',
            'published_at' => now(),
        ];
    }
}

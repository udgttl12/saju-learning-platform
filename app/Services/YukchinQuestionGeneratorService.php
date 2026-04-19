<?php

namespace App\Services;

class YukchinQuestionGeneratorService
{
    private const STEMS = [
        '甲' => ['element' => 'wood', 'yin_yang' => 'yang'],
        '乙' => ['element' => 'wood', 'yin_yang' => 'yin'],
        '丙' => ['element' => 'fire', 'yin_yang' => 'yang'],
        '丁' => ['element' => 'fire', 'yin_yang' => 'yin'],
        '戊' => ['element' => 'earth', 'yin_yang' => 'yang'],
        '己' => ['element' => 'earth', 'yin_yang' => 'yin'],
        '庚' => ['element' => 'metal', 'yin_yang' => 'yang'],
        '辛' => ['element' => 'metal', 'yin_yang' => 'yin'],
        '壬' => ['element' => 'water', 'yin_yang' => 'yang'],
        '癸' => ['element' => 'water', 'yin_yang' => 'yin'],
    ];

    private const TEN_GODS = [
        'same_same' => '비견',
        'same_diff' => '겁재',
        'produce_same' => '식신',
        'produce_diff' => '상관',
        'control_same' => '편재',
        'control_diff' => '정재',
        'controlled_same' => '편관',
        'controlled_diff' => '정관',
        'support_same' => '편인',
        'support_diff' => '정인',
    ];

    private const ELEMENT_FLOW = [
        'wood' => 'fire',
        'fire' => 'earth',
        'earth' => 'metal',
        'metal' => 'water',
        'water' => 'wood',
    ];

    public function generateMultipleChoice(string $dayMaster, string $targetStem): array
    {
        $correct = $this->resolveTenGod($dayMaster, $targetStem);
        $choices = $this->buildChoices($correct);

        return [
            'question_type' => 'multiple_choice',
            'source_type' => 'generated',
            'prompt_text' => "{$dayMaster} 일간 기준으로 {$targetStem}은(는) 어떤 육친/십성인가?",
            'choices_json' => $choices,
            'answer_payload_json' => [
                'correct_choice_index' => array_search($correct, $choices, true),
            ],
            'concept_key' => 'yukchin.ten-god.generated',
            'meta_json' => [
                'generator' => 'yukchin_relation',
                'day_master' => $dayMaster,
                'target_stem' => $targetStem,
                'correct_answer' => $correct,
            ],
            'explanation_text' => $this->buildExplanation($dayMaster, $targetStem, $correct),
        ];
    }

    public function resolveTenGod(string $dayMaster, string $targetStem): string
    {
        $day = self::STEMS[$dayMaster] ?? null;
        $target = self::STEMS[$targetStem] ?? null;

        if (!$day || !$target) {
            throw new \InvalidArgumentException('알 수 없는 천간 조합입니다.');
        }

        $samePolarity = $day['yin_yang'] === $target['yin_yang'];
        $relationKey = $this->resolveRelationKey($day['element'], $target['element'], $samePolarity);

        return self::TEN_GODS[$relationKey];
    }

    private function resolveRelationKey(string $dayElement, string $targetElement, bool $samePolarity): string
    {
        if ($dayElement === $targetElement) {
            return $samePolarity ? 'same_same' : 'same_diff';
        }

        if (self::ELEMENT_FLOW[$dayElement] === $targetElement) {
            return $samePolarity ? 'produce_same' : 'produce_diff';
        }

        if (self::ELEMENT_FLOW[$targetElement] === $dayElement) {
            return $samePolarity ? 'support_same' : 'support_diff';
        }

        $controlMap = [
            'wood' => 'earth',
            'earth' => 'water',
            'water' => 'fire',
            'fire' => 'metal',
            'metal' => 'wood',
        ];

        if ($controlMap[$dayElement] === $targetElement) {
            return $samePolarity ? 'control_same' : 'control_diff';
        }

        return $samePolarity ? 'controlled_same' : 'controlled_diff';
    }

    private function buildChoices(string $correct): array
    {
        $pool = array_values(array_diff(array_values(self::TEN_GODS), [$correct]));
        shuffle($pool);

        $choices = array_slice($pool, 0, 3);
        $choices[] = $correct;
        shuffle($choices);

        return $choices;
    }

    private function buildExplanation(string $dayMaster, string $targetStem, string $correct): string
    {
        $day = self::STEMS[$dayMaster];
        $target = self::STEMS[$targetStem];
        $samePolarity = $day['yin_yang'] === $target['yin_yang'] ? '같은' : '다른';

        return "{$dayMaster} 일간과 {$targetStem}의 오행 관계를 먼저 보고, 그다음 음양이 {$samePolarity}지 확인하면 {$correct}이 나옵니다.";
    }
}

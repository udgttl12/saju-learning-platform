<?php

namespace App\Services;

class YukchinQuestionGeneratorService
{
    private const STEMS = [
        '甲' => ['element' => 'wood', 'yin_yang' => 'yang', 'reading' => '갑'],
        '乙' => ['element' => 'wood', 'yin_yang' => 'yin', 'reading' => '을'],
        '丙' => ['element' => 'fire', 'yin_yang' => 'yang', 'reading' => '병'],
        '丁' => ['element' => 'fire', 'yin_yang' => 'yin', 'reading' => '정'],
        '戊' => ['element' => 'earth', 'yin_yang' => 'yang', 'reading' => '무'],
        '己' => ['element' => 'earth', 'yin_yang' => 'yin', 'reading' => '기'],
        '庚' => ['element' => 'metal', 'yin_yang' => 'yang', 'reading' => '경'],
        '辛' => ['element' => 'metal', 'yin_yang' => 'yin', 'reading' => '신'],
        '壬' => ['element' => 'water', 'yin_yang' => 'yang', 'reading' => '임'],
        '癸' => ['element' => 'water', 'yin_yang' => 'yin', 'reading' => '계'],
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

    private const ELEMENT_CONTROL = [
        'wood' => 'earth',
        'earth' => 'water',
        'water' => 'fire',
        'fire' => 'metal',
        'metal' => 'wood',
    ];

    private const ELEMENT_LABELS = [
        'wood' => '목',
        'fire' => '화',
        'earth' => '토',
        'metal' => '금',
        'water' => '수',
    ];

    private const YIN_YANG_LABELS = [
        'yang' => '양',
        'yin' => '음',
    ];

    public function getPoolSize(): int
    {
        return count(self::STEMS) * count(self::STEMS)
            + count(self::STEMS) * count(self::TEN_GODS);
    }

    public function buildExamQuestions(int $requestedCount): array
    {
        $pool = array_merge(
            $this->buildRelationQuestions(),
            $this->buildReverseQuestions(),
        );

        shuffle($pool);

        return array_slice($pool, 0, min($requestedCount, count($pool)));
    }

    public function generateMultipleChoice(string $dayMaster, string $targetStem): array
    {
        $correct = $this->resolveTenGod($dayMaster, $targetStem);
        $choices = $this->buildTenGodChoices($correct);

        return [
            'question_type' => 'multiple_choice',
            'source_type' => 'generated',
            'prompt_text' => "{$this->formatStem($dayMaster)} 일간 기준으로 {$this->formatStem($targetStem)}은 어떤 십성일까요?",
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
            'explanation_text' => $this->buildRelationExplanation($dayMaster, $targetStem, $correct),
        ];
    }

    public function resolveTenGod(string $dayMaster, string $targetStem): string
    {
        $day = self::STEMS[$dayMaster] ?? null;
        $target = self::STEMS[$targetStem] ?? null;

        if (! $day || ! $target) {
            throw new \InvalidArgumentException('유효하지 않은 천간 조합입니다.');
        }

        $samePolarity = $day['yin_yang'] === $target['yin_yang'];
        $relationKey = $this->resolveRelationKey($day['element'], $target['element'], $samePolarity);

        return self::TEN_GODS[$relationKey];
    }

    private function buildRelationQuestions(): array
    {
        $questions = [];

        foreach (array_keys(self::STEMS) as $dayMaster) {
            foreach (array_keys(self::STEMS) as $targetStem) {
                $correct = $this->resolveTenGod($dayMaster, $targetStem);

                $questions[] = $this->makeExamQuestion(
                    prompt: "{$this->formatStem($dayMaster)} 일간 기준으로 {$this->formatStem($targetStem)}은 어떤 십성일까요?",
                    choices: $this->buildTenGodChoices($correct),
                    correct: $correct,
                    explanation: $this->buildRelationExplanation($dayMaster, $targetStem, $correct),
                );
            }
        }

        return $questions;
    }

    private function buildReverseQuestions(): array
    {
        $questions = [];
        $tenGodLabels = array_values(self::TEN_GODS);

        foreach (array_keys(self::STEMS) as $dayMaster) {
            $relations = [];

            foreach (array_keys(self::STEMS) as $targetStem) {
                $relations[$targetStem] = $this->resolveTenGod($dayMaster, $targetStem);
            }

            foreach ($tenGodLabels as $tenGod) {
                $correctStem = array_search($tenGod, $relations, true);

                if (! is_string($correctStem)) {
                    continue;
                }

                $choices = $this->buildStemChoices($correctStem);

                $questions[] = $this->makeExamQuestion(
                    prompt: "{$this->formatStem($dayMaster)} 일간 기준으로 {$tenGod}에 해당하는 천간은 무엇일까요?",
                    choices: array_map(fn (string $stem) => $this->formatStem($stem), $choices),
                    correct: $this->formatStem($correctStem),
                    explanation: $this->buildReverseExplanation($dayMaster, $correctStem, $tenGod),
                );
            }
        }

        return $questions;
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

        if (self::ELEMENT_CONTROL[$dayElement] === $targetElement) {
            return $samePolarity ? 'control_same' : 'control_diff';
        }

        return $samePolarity ? 'controlled_same' : 'controlled_diff';
    }

    private function buildTenGodChoices(string $correct): array
    {
        $pool = array_values(array_diff(array_values(self::TEN_GODS), [$correct]));
        shuffle($pool);

        $choices = array_slice($pool, 0, 3);
        $choices[] = $correct;
        shuffle($choices);

        return $choices;
    }

    private function buildStemChoices(string $correctStem): array
    {
        $pool = array_values(array_diff(array_keys(self::STEMS), [$correctStem]));
        shuffle($pool);

        $choices = array_slice($pool, 0, 3);
        $choices[] = $correctStem;
        shuffle($choices);

        return $choices;
    }

    private function buildRelationExplanation(string $dayMaster, string $targetStem, string $correct): string
    {
        $day = self::STEMS[$dayMaster];
        $target = self::STEMS[$targetStem];
        $samePolarityLabel = $day['yin_yang'] === $target['yin_yang'] ? '같은' : '다른';

        return "{$this->formatStem($dayMaster)}과 {$this->formatStem($targetStem)}의 오행 관계를 먼저 보고, "
            ."음양이 {$samePolarityLabel}지 확인하면 {$correct}이 나옵니다.";
    }

    private function buildReverseExplanation(string $dayMaster, string $targetStem, string $tenGod): string
    {
        $day = self::STEMS[$dayMaster];
        $target = self::STEMS[$targetStem];

        return "{$this->formatStem($dayMaster)} 기준에서 {$this->formatStem($targetStem)}은 "
            ."{$this->describeStemTrait($target['element'], $target['yin_yang'])}이므로 {$tenGod}입니다.";
    }

    private function describeStemTrait(string $element, string $yinYang): string
    {
        return self::ELEMENT_LABELS[$element].' 오행의 '.self::YIN_YANG_LABELS[$yinYang].' 기운';
    }

    private function makeExamQuestion(string $prompt, array $choices, string $correct, string $explanation): array
    {
        $shuffledChoices = $choices;
        shuffle($shuffledChoices);

        $choicePayloads = [];
        $correctId = null;

        foreach (array_values($shuffledChoices) as $index => $choice) {
            $choicePayloads[] = [
                'id' => $index,
                'text' => $choice,
            ];

            if ($choice === $correct) {
                $correctId = $index;
            }
        }

        return [
            'hanja_char_id' => null,
            'has_char' => false,
            'char_value' => '',
            'reading_ko' => '',
            'meaning_ko' => $correct,
            'element' => null,
            'prompt' => $prompt,
            'correct_id' => $correctId ?? 0,
            'choices' => $choicePayloads,
            'explanation' => $explanation,
        ];
    }

    private function formatStem(string $stem): string
    {
        return sprintf('%s(%s)', $stem, self::STEMS[$stem]['reading']);
    }
}

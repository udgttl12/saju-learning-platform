<?php

namespace App\Services;

class TwelveShinsalQuestionGeneratorService
{
    private const BRANCHES = [
        '子' => '자',
        '丑' => '축',
        '寅' => '인',
        '卯' => '묘',
        '辰' => '진',
        '巳' => '사',
        '午' => '오',
        '未' => '미',
        '申' => '신',
        '酉' => '유',
        '戌' => '술',
        '亥' => '해',
    ];

    private const GROUPS = [
        'water' => [
            'label' => '수국(申子辰)',
            'branches' => ['申', '子', '辰'],
            'start' => '巳',
        ],
        'wood' => [
            'label' => '목국(亥卯未)',
            'branches' => ['亥', '卯', '未'],
            'start' => '申',
        ],
        'fire' => [
            'label' => '화국(寅午戌)',
            'branches' => ['寅', '午', '戌'],
            'start' => '亥',
        ],
        'metal' => [
            'label' => '금국(巳酉丑)',
            'branches' => ['巳', '酉', '丑'],
            'start' => '寅',
        ],
    ];

    private const SHINSAL_ORDER = [
        '겁살',
        '재살',
        '천살',
        '지살',
        '도화살',
        '월살',
        '망신살',
        '장성살',
        '반안살',
        '역마살',
        '육해살',
        '화개살',
    ];

    private const SHINSAL_SUMMARIES = [
        '겁살' => '손실·격변·결단의 기운',
        '재살' => '시비·관재·위험과 연결되는 기운',
        '천살' => '시련·불가항력·종교성과 연결되는 기운',
        '지살' => '이동·이사·새출발을 상징하는 기운',
        '도화살' => '매력·인기·노출 자산과 연결되는 기운',
        '월살' => '고립·소진·회복을 상징하는 기운',
        '망신살' => '공개 노출·체면 손상·구설을 상징하는 기운',
        '장성살' => '리더십·권위·주도성을 상징하는 기운',
        '반안살' => '승진·명예·안정된 자리를 상징하는 기운',
        '역마살' => '이동·활동·환경 변화를 상징하는 기운',
        '육해살' => '방해·건강·돌봄과 연결되는 기운',
        '화개살' => '학문·예술·종교·고독을 상징하는 기운',
    ];

    public function getPoolSize(): int
    {
        return count($this->buildQuestionPool());
    }

    public function buildExamQuestions(int $requestedCount): array
    {
        $pool = $this->buildQuestionPool();
        shuffle($pool);

        return array_slice($pool, 0, min($requestedCount, count($pool)));
    }

    private function buildQuestionPool(): array
    {
        return array_merge(
            $this->buildRelationQuestions(),
            $this->buildReverseQuestions(),
            $this->buildSequenceQuestions(),
            $this->buildMeaningQuestions(),
            $this->buildGroupQuestions(),
        );
    }

    private function buildRelationQuestions(): array
    {
        $questions = [];

        foreach (array_keys(self::BRANCHES) as $baseBranch) {
            foreach (array_keys(self::BRANCHES) as $targetBranch) {
                $correct = $this->resolveShinsal($baseBranch, $targetBranch);

                $questions[] = $this->makeExamQuestion(
                    prompt: "일지가 {$this->formatBranch($baseBranch)}일 때 {$this->formatBranch($targetBranch)}는 어떤 신살일까요?",
                    choices: $this->buildShinsalChoices($correct),
                    correct: $correct,
                    explanation: $this->buildRelationExplanation($baseBranch, $targetBranch, $correct),
                );
            }
        }

        return $questions;
    }

    private function buildReverseQuestions(): array
    {
        $questions = [];

        foreach (array_keys(self::BRANCHES) as $baseBranch) {
            $mapping = $this->mappingForBaseBranch($baseBranch);

            foreach (self::SHINSAL_ORDER as $shinsal) {
                $correctBranch = array_search($shinsal, $mapping, true);

                if (! is_string($correctBranch)) {
                    continue;
                }

                $choices = array_map(
                    fn (string $branch) => $this->formatBranch($branch),
                    $this->buildBranchChoices($correctBranch),
                );

                $questions[] = $this->makeExamQuestion(
                    prompt: "일지가 {$this->formatBranch($baseBranch)}일 때 {$shinsal}에 해당하는 지지는 무엇일까요?",
                    choices: $choices,
                    correct: $this->formatBranch($correctBranch),
                    explanation: $this->buildRelationExplanation($baseBranch, $correctBranch, $shinsal),
                );
            }
        }

        return $questions;
    }

    private function buildSequenceQuestions(): array
    {
        $questions = [];

        foreach (array_slice(self::SHINSAL_ORDER, 0, -1) as $index => $current) {
            $correct = self::SHINSAL_ORDER[$index + 1];

            $questions[] = $this->makeExamQuestion(
                prompt: "12신살 순서에서 {$current} 다음에 오는 신살은 무엇일까요?",
                choices: $this->buildShinsalChoices($correct),
                correct: $correct,
                explanation: "12신살의 기본 순서는 겁·재·천·지·도화·월·망·장·반·역·육·화입니다. {$current} 다음은 {$correct}입니다.",
            );
        }

        return $questions;
    }

    private function buildMeaningQuestions(): array
    {
        $questions = [];

        foreach (self::SHINSAL_SUMMARIES as $shinsal => $summary) {
            $questions[] = $this->makeExamQuestion(
                prompt: "다음 설명에 가장 잘 맞는 신살은 무엇일까요? \"{$summary}\"",
                choices: $this->buildShinsalChoices($shinsal),
                correct: $shinsal,
                explanation: "{$shinsal}은 {$summary}으로 기억하면 핵심을 빠르게 잡을 수 있습니다.",
            );
        }

        return $questions;
    }

    private function buildGroupQuestions(): array
    {
        $questions = [];

        foreach (array_keys(self::BRANCHES) as $branch) {
            $group = $this->groupForBranch($branch);
            $groupLabels = array_column(self::GROUPS, 'label');

            $questions[] = $this->makeExamQuestion(
                prompt: "{$this->formatBranch($branch)}가 속한 삼합은 무엇일까요?",
                choices: $this->buildChoicesFromPool($group['label'], $groupLabels),
                correct: $group['label'],
                explanation: "{$this->formatBranch($branch)}는 {$group['label']}에 속하므로, 이 삼합을 기준으로 12신살을 배치합니다.",
            );
        }

        return $questions;
    }

    private function resolveShinsal(string $baseBranch, string $targetBranch): string
    {
        $mapping = $this->mappingForBaseBranch($baseBranch);

        return $mapping[$targetBranch];
    }

    private function mappingForBaseBranch(string $baseBranch): array
    {
        $group = $this->groupForBranch($baseBranch);
        $rotatedBranches = $this->rotateBranchesFrom($group['start']);
        $mapping = [];

        foreach (self::SHINSAL_ORDER as $index => $shinsal) {
            $mapping[$rotatedBranches[$index]] = $shinsal;
        }

        return $mapping;
    }

    private function rotateBranchesFrom(string $startBranch): array
    {
        $branches = array_keys(self::BRANCHES);
        $startIndex = array_search($startBranch, $branches, true);

        return array_merge(
            array_slice($branches, $startIndex),
            array_slice($branches, 0, $startIndex),
        );
    }

    private function groupForBranch(string $branch): array
    {
        foreach (self::GROUPS as $group) {
            if (in_array($branch, $group['branches'], true)) {
                return $group;
            }
        }

        throw new \InvalidArgumentException('유효하지 않은 지지입니다.');
    }

    private function buildRelationExplanation(string $baseBranch, string $targetBranch, string $shinsal): string
    {
        $group = $this->groupForBranch($baseBranch);

        return "{$this->formatBranch($baseBranch)}는 {$group['label']}에 속합니다. "
            ."이 기준에서 {$this->formatBranch($targetBranch)}는 {$shinsal} 자리에 배치됩니다.";
    }

    private function buildShinsalChoices(string $correct): array
    {
        return $this->buildChoicesFromPool($correct, self::SHINSAL_ORDER);
    }

    private function buildBranchChoices(string $correctBranch): array
    {
        return $this->buildChoicesFromPool($correctBranch, array_keys(self::BRANCHES));
    }

    private function buildChoicesFromPool(string $correct, array $pool): array
    {
        $candidates = array_values(array_diff($pool, [$correct]));
        shuffle($candidates);

        $choices = array_slice($candidates, 0, 3);
        $choices[] = $correct;
        shuffle($choices);

        return $choices;
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

    private function formatBranch(string $branch): string
    {
        return sprintf('%s(%s)', $branch, self::BRANCHES[$branch]);
    }
}

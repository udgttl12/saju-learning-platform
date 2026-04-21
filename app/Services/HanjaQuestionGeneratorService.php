<?php

namespace App\Services;

use App\Models\HanjaChar;
use Illuminate\Support\Collection;

class HanjaQuestionGeneratorService
{
    private const CATEGORY_LABELS = [
        'five_elements' => '오행',
        'heavenly_stems' => '천간',
        'earthly_branches' => '지지',
    ];

    private const ELEMENT_LABELS = [
        'wood' => '목',
        'fire' => '화',
        'earth' => '토',
        'metal' => '금',
        'water' => '수',
        'none' => '없음',
    ];

    private const YIN_YANG_LABELS = [
        'yang' => '양',
        'yin' => '음',
        'neutral' => '중성',
    ];

    private const YIN_YANG_CHOICES = ['양', '음', '중성', '판단 불가'];

    public function getPoolSize(string $category): int
    {
        return count($this->buildQuestionPool($this->loadChars($category), $category));
    }

    public function buildExamQuestions(string $category, int $requestedCount): array
    {
        $pool = $this->buildQuestionPool($this->loadChars($category), $category);
        shuffle($pool);

        return array_slice($pool, 0, min($requestedCount, count($pool)));
    }

    private function loadChars(string $category): Collection
    {
        $query = HanjaChar::query()
            ->where('publish_status', 'published')
            ->orderBy('id');

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        return $query->get();
    }

    private function buildQuestionPool(Collection $chars, string $category): array
    {
        if ($chars->count() < 4) {
            return [];
        }

        $quizMeanings = $this->quizMeaningMap($chars);

        return array_values(array_filter(array_merge(
            $this->buildCharToMeaningQuestions($chars, $quizMeanings),
            $this->buildCharToReadingQuestions($chars),
            $this->buildCharToElementQuestions($chars),
            $this->buildCharToYinYangQuestions($chars),
            $this->buildMeaningToCharQuestions($chars, $quizMeanings),
            $this->buildReadingToCharQuestions($chars),
            $this->buildMeaningReadingToCharQuestions($chars),
            $this->buildMeaningToReadingQuestions($chars, $quizMeanings),
            $this->buildReadingToMeaningQuestions($chars, $quizMeanings),
            $this->buildMeaningToElementQuestions($chars, $quizMeanings),
            $this->buildElementReadingToCharQuestions($chars),
            $this->buildCharToCategoryQuestions($chars, $category),
        )));
    }

    private function buildCharToMeaningQuestions(Collection $chars, Collection $quizMeanings): array
    {
        $meaningChoices = $this->uniqueQuizMeanings($quizMeanings);

        if (count($meaningChoices) < 4) {
            return [];
        }

        return $chars
            ->map(function (HanjaChar $char) use ($meaningChoices, $quizMeanings) {
                $quizMeaning = $this->quizMeaningFor($quizMeanings, $char);

                if (! $this->isFilledValue($quizMeaning)) {
                    return null;
                }

                return $this->makeQuestion(
                    char: $char,
                    prompt: "한자 {$char->char_value}의 뜻으로 맞는 것은 무엇일까요?",
                    choices: $this->buildChoices($quizMeaning, $meaningChoices),
                    correct: $quizMeaning,
                    explanation: "{$char->char_value}의 뜻은 {$quizMeaning}, 음은 {$char->reading_ko}입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildCharToReadingQuestions(Collection $chars): array
    {
        $readingChoices = $this->uniqueFieldValues($chars, 'reading_ko');

        if (count($readingChoices) < 4) {
            return [];
        }

        return $chars
            ->map(fn (HanjaChar $char) => $this->makeQuestion(
                char: $char,
                prompt: "한자 {$char->char_value}의 음으로 맞는 것은 무엇일까요?",
                choices: $this->buildChoices($char->reading_ko, $readingChoices),
                correct: $char->reading_ko,
                explanation: "{$char->char_value}의 음은 {$char->reading_ko}, 뜻은 {$char->meaning_ko}입니다.",
            ))
            ->filter()
            ->values()
            ->all();
    }

    private function buildCharToElementQuestions(Collection $chars): array
    {
        $elementChoices = array_values(array_filter(
            self::ELEMENT_LABELS,
            fn (string $label) => $label !== '없음',
        ));

        return $chars
            ->map(function (HanjaChar $char) use ($elementChoices) {
                $correct = $this->elementLabel($char);

                return $this->makeQuestion(
                    char: $char,
                    prompt: "한자 {$char->char_value}가 상징하는 오행은 무엇일까요?",
                    choices: $this->buildChoices($correct, $elementChoices),
                    correct: $correct,
                    explanation: "{$char->char_value}는 {$correct} 기운을 상징하는 한자입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildCharToYinYangQuestions(Collection $chars): array
    {
        return $chars
            ->map(function (HanjaChar $char) {
                $correct = $this->yinYangLabel($char);

                return $this->makeQuestion(
                    char: $char,
                    prompt: "한자 {$char->char_value}의 음양 값은 무엇일까요?",
                    choices: $this->buildChoices($correct, self::YIN_YANG_CHOICES),
                    correct: $correct,
                    explanation: "{$char->char_value}의 음양 값은 {$correct}입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildMeaningToCharQuestions(Collection $chars, Collection $quizMeanings): array
    {
        return $chars
            ->filter(fn (HanjaChar $char) => $this->hasUniqueQuizMeaning($quizMeanings, $char))
            ->map(function (HanjaChar $char) use ($chars, $quizMeanings) {
                $quizMeaning = $this->quizMeaningFor($quizMeanings, $char);

                if (! $this->isFilledValue($quizMeaning)) {
                    return null;
                }

                return $this->makeQuestion(
                    char: $char,
                    prompt: "\"{$quizMeaning}\"에 해당하는 한자는 무엇일까요?",
                    choices: $this->buildCharChoices($char, $chars),
                    correct: $char->char_value,
                    explanation: "{$char->char_value}는 {$quizMeaning}를 뜻하고, 음은 {$char->reading_ko}입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildReadingToCharQuestions(Collection $chars): array
    {
        return $chars
            ->filter(fn (HanjaChar $char) => $this->isUniqueFieldValue($chars, 'reading_ko', $char->reading_ko))
            ->map(fn (HanjaChar $char) => $this->makeQuestion(
                char: $char,
                prompt: "\"{$char->reading_ko}\"로 읽는 한자는 무엇일까요?",
                choices: $this->buildCharChoices($char, $chars),
                correct: $char->char_value,
                explanation: "{$char->char_value}의 음은 {$char->reading_ko}, 뜻은 {$char->meaning_ko}입니다.",
            ))
            ->filter()
            ->values()
            ->all();
    }

    private function buildMeaningReadingToCharQuestions(Collection $chars): array
    {
        return $chars
            ->map(fn (HanjaChar $char) => $this->makeQuestion(
                char: $char,
                prompt: "\"{$char->meaning_ko} ({$char->reading_ko})\"에 해당하는 한자는 무엇일까요?",
                choices: $this->buildCharChoices($char, $chars),
                correct: $char->char_value,
                explanation: "{$char->char_value}는 {$char->meaning_ko} ({$char->reading_ko})에 해당합니다.",
            ))
            ->filter()
            ->values()
            ->all();
    }

    private function buildMeaningToReadingQuestions(Collection $chars, Collection $quizMeanings): array
    {
        $readingChoices = $this->uniqueFieldValues($chars, 'reading_ko');

        if (count($readingChoices) < 4) {
            return [];
        }

        return $chars
            ->filter(fn (HanjaChar $char) => $this->hasUniqueQuizMeaning($quizMeanings, $char))
            ->map(function (HanjaChar $char) use ($readingChoices, $quizMeanings) {
                $quizMeaning = $this->quizMeaningFor($quizMeanings, $char);

                if (! $this->isFilledValue($quizMeaning)) {
                    return null;
                }

                return $this->makeQuestion(
                    char: $char,
                    prompt: "\"{$quizMeaning}\"에 해당하는 한자의 음은 무엇일까요?",
                    choices: $this->buildChoices($char->reading_ko, $readingChoices),
                    correct: $char->reading_ko,
                    explanation: "{$quizMeaning}에 해당하는 한자는 {$char->char_value}, 음은 {$char->reading_ko}입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildReadingToMeaningQuestions(Collection $chars, Collection $quizMeanings): array
    {
        $meaningChoices = $this->uniqueQuizMeanings($quizMeanings);

        if (count($meaningChoices) < 4) {
            return [];
        }

        return $chars
            ->filter(fn (HanjaChar $char) => $this->isUniqueFieldValue($chars, 'reading_ko', $char->reading_ko))
            ->map(function (HanjaChar $char) use ($meaningChoices, $quizMeanings) {
                $quizMeaning = $this->quizMeaningFor($quizMeanings, $char);

                if (! $this->isFilledValue($quizMeaning)) {
                    return null;
                }

                return $this->makeQuestion(
                    char: $char,
                    prompt: "\"{$char->reading_ko}\"로 읽는 한자의 뜻은 무엇일까요?",
                    choices: $this->buildChoices($quizMeaning, $meaningChoices),
                    correct: $quizMeaning,
                    explanation: "{$char->reading_ko}로 읽는 한자는 {$char->char_value}이고, 뜻은 {$quizMeaning}입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildMeaningToElementQuestions(Collection $chars, Collection $quizMeanings): array
    {
        $elementChoices = array_values(array_filter(
            self::ELEMENT_LABELS,
            fn (string $label) => $label !== '없음',
        ));

        return $chars
            ->filter(fn (HanjaChar $char) => $this->hasUniqueQuizMeaning($quizMeanings, $char))
            ->map(function (HanjaChar $char) use ($elementChoices, $quizMeanings) {
                $correct = $this->elementLabel($char);
                $quizMeaning = $this->quizMeaningFor($quizMeanings, $char);

                if (! $this->isFilledValue($quizMeaning)) {
                    return null;
                }

                return $this->makeQuestion(
                    char: $char,
                    prompt: "\"{$quizMeaning}\"에 해당하는 한자가 상징하는 오행은 무엇일까요?",
                    choices: $this->buildChoices($correct, $elementChoices),
                    correct: $correct,
                    explanation: "{$quizMeaning}에 해당하는 한자 {$char->char_value}는 {$correct} 기운을 상징합니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildElementReadingToCharQuestions(Collection $chars): array
    {
        return $chars
            ->map(function (HanjaChar $char) use ($chars) {
                $elementLabel = $this->elementLabel($char);

                return $this->makeQuestion(
                    char: $char,
                    prompt: "{$elementLabel} 기운이고 음이 \"{$char->reading_ko}\"인 한자는 무엇일까요?",
                    choices: $this->buildCharChoices($char, $chars),
                    correct: $char->char_value,
                    explanation: "{$elementLabel} 기운에 {$char->reading_ko}로 읽는 한자는 {$char->char_value}입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildCharToCategoryQuestions(Collection $chars, string $category): array
    {
        if ($category !== 'all') {
            return [];
        }

        $categoryChoices = [...array_values(self::CATEGORY_LABELS), '기타'];

        return $chars
            ->map(function (HanjaChar $char) use ($categoryChoices) {
                $correct = self::CATEGORY_LABELS[$char->category] ?? '기타';

                return $this->makeQuestion(
                    char: $char,
                    prompt: "한자 {$char->char_value}는 어떤 분류에 속할까요?",
                    choices: $this->buildChoices($correct, $categoryChoices),
                    correct: $correct,
                    explanation: "{$char->char_value}는 {$correct} 분류에 속하는 한자입니다.",
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    private function makeQuestion(HanjaChar $char, string $prompt, array $choices, string $correct, string $explanation): ?array
    {
        if (! $this->hasValidChoices($choices, $correct)) {
            return null;
        }

        $shuffledChoices = $choices;
        shuffle($shuffledChoices);

        $choicePayloads = [];
        $correctId = 0;

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
            'hanja_char_id' => $char->id,
            'has_char' => false,
            'char_value' => '',
            'reading_ko' => '',
            'meaning_ko' => $correct,
            'element' => null,
            'prompt' => $prompt,
            'correct_id' => $correctId,
            'choices' => $choicePayloads,
            'explanation' => $explanation,
        ];
    }

    private function buildCharChoices(HanjaChar $correctChar, Collection $chars): array
    {
        $pool = $chars
            ->reject(fn (HanjaChar $char) => $char->id === $correctChar->id)
            ->pluck('char_value')
            ->filter(fn ($value) => $this->isFilledValue($value))
            ->unique()
            ->values()
            ->all();

        shuffle($pool);

        $choices = array_slice($pool, 0, 3);
        $choices[] = $correctChar->char_value;

        return $choices;
    }

    private function buildChoices(?string $correct, array $pool): array
    {
        if (! $this->isFilledValue($correct)) {
            return [];
        }

        $choices = array_values(array_unique(array_filter(
            $pool,
            fn ($value) => $this->isFilledValue($value) && $value !== $correct,
        )));

        if (count($choices) < 3) {
            return [];
        }

        shuffle($choices);

        $choices = array_slice($choices, 0, 3);
        $choices[] = $correct;

        return $choices;
    }

    private function hasValidChoices(array $choices, string $correct): bool
    {
        if (count($choices) !== 4) {
            return false;
        }

        if (! in_array($correct, $choices, true)) {
            return false;
        }

        return count(array_unique($choices)) === 4;
    }

    private function quizMeaningMap(Collection $chars): Collection
    {
        return $chars->mapWithKeys(fn (HanjaChar $char) => [
            $char->id => $this->sanitizeMeaning($char->meaning_ko, $char->reading_ko),
        ]);
    }

    private function uniqueQuizMeanings(Collection $quizMeanings): array
    {
        return $quizMeanings
            ->filter(fn ($value) => $this->isFilledValue($value))
            ->unique()
            ->values()
            ->all();
    }

    private function quizMeaningFor(Collection $quizMeanings, HanjaChar $char): ?string
    {
        $quizMeaning = $quizMeanings->get($char->id);

        return $this->isFilledValue($quizMeaning) ? $quizMeaning : null;
    }

    private function hasUniqueQuizMeaning(Collection $quizMeanings, HanjaChar $char): bool
    {
        $quizMeaning = $this->quizMeaningFor($quizMeanings, $char);

        if (! $this->isFilledValue($quizMeaning)) {
            return false;
        }

        return $quizMeanings
            ->filter(fn ($value) => $value === $quizMeaning)
            ->count() === 1;
    }

    private function sanitizeMeaning(?string $meaning, ?string $reading): ?string
    {
        if (! $this->isFilledValue($meaning)) {
            return null;
        }

        $sanitized = trim($meaning);
        $normalizedReading = $this->isFilledValue($reading) ? trim($reading) : '';

        if ($normalizedReading !== '') {
            $patterns = [
                '/\s*의\s*'.preg_quote($normalizedReading, '/').'\s*$/u',
                '/\s+'.preg_quote($normalizedReading, '/').'\s*$/u',
            ];

            foreach ($patterns as $pattern) {
                $nextSanitized = preg_replace($pattern, '', $sanitized);

                if (is_string($nextSanitized) && $nextSanitized !== $sanitized) {
                    $sanitized = $nextSanitized;
                    break;
                }
            }
        }

        $sanitized = $this->normalizeQuizMeaning($sanitized);

        if (! $this->isFilledValue($sanitized)) {
            return null;
        }

        if ($normalizedReading !== '' && $this->endsWithReadingToken($sanitized, $normalizedReading)) {
            return null;
        }

        return $sanitized;
    }

    private function normalizeQuizMeaning(string $meaning): string
    {
        $normalized = preg_replace('/\s+/u', ' ', trim($meaning)) ?? trim($meaning);
        $normalized = preg_replace('/\s*,\s*/u', ', ', $normalized) ?? $normalized;
        $normalized = preg_replace('/(?:\s|[,·ㆍ\/])+$/u', '', $normalized) ?? $normalized;

        return trim($normalized);
    }

    private function endsWithReadingToken(string $meaning, string $reading): bool
    {
        if ($meaning === $reading) {
            return true;
        }

        return preg_match('/(?:\s+|의\s*)'.preg_quote($reading, '/').'\s*$/u', $meaning) === 1;
    }

    private function uniqueFieldValues(Collection $chars, string $field): array
    {
        return $chars
            ->pluck($field)
            ->filter(fn ($value) => $this->isFilledValue($value))
            ->unique()
            ->values()
            ->all();
    }

    private function isUniqueFieldValue(Collection $chars, string $field, ?string $value): bool
    {
        if (! $this->isFilledValue($value)) {
            return false;
        }

        return $chars->where($field, $value)->count() === 1;
    }

    private function isFilledValue(mixed $value): bool
    {
        return is_string($value) && trim($value) !== '';
    }

    private function elementLabel(HanjaChar $char): string
    {
        return self::ELEMENT_LABELS[$char->element] ?? '없음';
    }

    private function yinYangLabel(HanjaChar $char): string
    {
        return self::YIN_YANG_LABELS[$char->yin_yang] ?? '판단 불가';
    }
}

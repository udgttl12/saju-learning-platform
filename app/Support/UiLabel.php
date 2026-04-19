<?php

namespace App\Support;

class UiLabel
{
    public static function publishStatuses(): array
    {
        return [
            'draft' => '임시저장',
            'published' => '공개',
            'archived' => '보관',
        ];
    }

    public static function publishStatus(?string $value, string $default = '-'): string
    {
        return self::map($value, self::publishStatuses(), $default);
    }

    public static function reviewStages(): array
    {
        return [
            'new' => '신규',
            'learning' => '학습 중',
            'review' => '복습 중',
            'reviewing' => '복습 중',
            'lapsed' => '다시 복습',
            'mastered' => '숙달',
        ];
    }

    public static function reviewStage(?string $value, string $default = '-'): string
    {
        return self::map($value, self::reviewStages(), $default);
    }

    public static function hanjaCategories(): array
    {
        return [
            'all' => '전체',
            'five_elements' => '오행',
            'heavenly_stems' => '천간',
            'earthly_branches' => '지지',
            'term' => '용어',
        ];
    }

    public static function hanjaCategory(?string $value, string $default = '-'): string
    {
        return self::map($value, self::hanjaCategories(), $default);
    }

    public static function elements(): array
    {
        return [
            'wood' => '목(木)',
            'fire' => '화(火)',
            'earth' => '토(土)',
            'metal' => '금(金)',
            'water' => '수(水)',
            'none' => '없음',
        ];
    }

    public static function element(?string $value, string $default = '-'): string
    {
        return self::map($value, self::elements(), $default);
    }

    public static function lessonTypes(): array
    {
        return [
            'concept' => '개념',
            'example_chart' => '예시 명식',
            'hanja_card' => '한자 카드',
            'practice' => '연습',
            'lecture' => '강의',
        ];
    }

    public static function lessonType(?string $value, string $default = '-'): string
    {
        return self::map($value, self::lessonTypes(), $default);
    }

    public static function questionTypes(): array
    {
        return [
            'multiple_choice' => '객관식',
            'true_false' => 'O/X',
            'short_answer' => '단답형',
            'self_check' => '자가 점검',
        ];
    }

    public static function questionType(?string $value, string $default = '-'): string
    {
        return self::map($value, self::questionTypes(), $default);
    }

    private static function map(?string $value, array $labels, string $default): string
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return $labels[$value] ?? $value;
    }
}

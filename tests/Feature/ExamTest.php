<?php

namespace Tests\Feature;

use App\Models\HanjaChar;
use App\Services\HanjaQuestionGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_index_uses_standard_count_options_for_generated_categories(): void
    {
        $this->seedHanjaCategories();

        $response = $this->get(route('exam.index'));
        $countOptions = $response->viewData('countOptions');

        $response->assertOk();
        $this->assertSame([10, 20, 50, 100], $countOptions);
    }

    public function test_exam_index_prefers_twenty_as_default_count(): void
    {
        $response = $this->get(route('exam.index'));

        $response->assertOk();
        $response->assertSee('chosenCount: 20', false);
        $response->assertSee('options.includes(20)', false);
    }

    public function test_exam_index_uses_generated_pool_counts_for_hanja_categories(): void
    {
        $this->seedHanjaCategories();

        $response = $this->get(route('exam.index'));
        $categories = $response->viewData('categories');

        $response->assertOk();
        $this->assertGreaterThan(5, $categories['five_elements']['count']);
        $this->assertGreaterThan(10, $categories['heavenly_stems']['count']);
        $this->assertGreaterThan(12, $categories['earthly_branches']['count']);
    }

    public function test_exam_index_uses_generated_pool_counts_for_dynamic_categories(): void
    {
        $response = $this->get(route('exam.index'));
        $categories = $response->viewData('categories');

        $response->assertOk();
        $this->assertGreaterThan(100, $categories['twelve_shinsal']['count']);
        $this->assertGreaterThan(100, $categories['yukchin']['count']);
    }

    public function test_generated_hanja_exam_can_start_with_more_questions_than_source_chars(): void
    {
        $this->createPublishedHanjaChars('five_elements', 5);

        $response = $this->post(route('exam.start'), [
            'category' => 'five_elements',
            'count' => 20,
        ]);

        $response->assertRedirect(route('exam.play'));
        $response->assertSessionHas('exam_data', fn (array $examData): bool => $this->matchesGeneratedExamPayload(
            examData: $examData,
            category: 'five_elements',
            expectedCount: 20,
            expectsHanjaCharId: true,
        ));
    }

    public function test_generated_hanja_questions_strip_readings_from_meaning_based_prompts_and_choices(): void
    {
        $this->createProblematicHeavenlyStems();

        $questions = app(HanjaQuestionGeneratorService::class)->buildExamQuestions('heavenly_stems', 200);
        $meaningToReadingPrompts = array_values(array_map(
            fn (array $question) => $question['prompt'],
            array_filter(
                $questions,
                fn (array $question): bool => str_contains($question['prompt'], '에 해당하는 한자의 음은 무엇일까요?'),
            ),
        ));
        $readingToMeaningChoices = [];

        foreach (array_filter(
            $questions,
            fn (array $question): bool => str_contains($question['prompt'], '로 읽는 한자의 뜻은 무엇일까요?'),
        ) as $question) {
            $readingToMeaningChoices = array_merge($readingToMeaningChoices, array_column($question['choices'], 'text'));
        }

        $this->assertNotEmpty($meaningToReadingPrompts);
        $this->assertNotEmpty($readingToMeaningChoices);
        $this->assertContains('"날을 세우는"에 해당하는 한자의 음은 무엇일까요?', $meaningToReadingPrompts);
        $this->assertNotContains('"날을 세우는 경"에 해당하는 한자의 음은 무엇일까요?', $meaningToReadingPrompts);
        $this->assertContains('굽은 새싹', $readingToMeaningChoices);
        $this->assertContains('갑옷, 시작', $readingToMeaningChoices);
        $this->assertNotContains('굽은 새싹의 을', $readingToMeaningChoices);
        $this->assertNotContains('갑옷, 시작의 갑', $readingToMeaningChoices);
    }

    public function test_generated_hanja_exam_skips_ambiguous_sanitized_meanings_but_still_starts(): void
    {
        $this->createAmbiguousHeavenlyStems();

        $questions = app(HanjaQuestionGeneratorService::class)->buildExamQuestions('heavenly_stems', 100);
        $meaningPromptTexts = array_values(array_map(
            fn (array $question) => $question['prompt'],
            array_filter($questions, function (array $question): bool {
                return str_contains($question['prompt'], '에 해당하는 한자의 음은 무엇일까요?')
                    || str_contains($question['prompt'], '에 해당하는 한자가 상징하는 오행은 무엇일까요?')
                    || (str_contains($question['prompt'], '에 해당하는 한자는 무엇일까요?') && ! str_contains($question['prompt'], '('));
            }),
        ));

        $this->assertNotEmpty($questions);
        $this->assertNotContains('"큰 물"에 해당하는 한자는 무엇일까요?', $meaningPromptTexts);
        $this->assertNotContains('"큰 물"에 해당하는 한자의 음은 무엇일까요?', $meaningPromptTexts);
        $this->assertNotContains('"큰 물"에 해당하는 한자가 상징하는 오행은 무엇일까요?', $meaningPromptTexts);

        $response = $this->post(route('exam.start'), [
            'category' => 'heavenly_stems',
            'count' => 10,
        ]);

        $response->assertRedirect(route('exam.play'));
        $response->assertSessionHas('exam_data', function (array $examData): bool {
            return $examData['category'] === 'heavenly_stems'
                && $examData['requested_count'] === 10
                && $examData['actual_count'] === 10
                && count($examData['questions']) === 10;
        });
    }

    public function test_generated_yukchin_exam_can_start_without_seeded_quiz_items(): void
    {
        $response = $this->post(route('exam.start'), [
            'category' => 'yukchin',
            'count' => 20,
        ]);

        $response->assertRedirect(route('exam.play'));
        $response->assertSessionHas('exam_data', fn (array $examData): bool => $this->matchesGeneratedExamPayload(
            examData: $examData,
            category: 'yukchin',
            expectedCount: 20,
            expectsHanjaCharId: false,
        ));
    }

    public function test_generated_twelve_shinsal_exam_can_start_without_seeded_quiz_items(): void
    {
        $response = $this->post(route('exam.start'), [
            'category' => 'twelve_shinsal',
            'count' => 20,
        ]);

        $response->assertRedirect(route('exam.play'));
        $response->assertSessionHas('exam_data', fn (array $examData): bool => $this->matchesGeneratedExamPayload(
            examData: $examData,
            category: 'twelve_shinsal',
            expectedCount: 20,
            expectsHanjaCharId: false,
        ));
    }

    public function test_exam_play_includes_auto_advance_for_correct_answers(): void
    {
        $response = $this->withSession([
            'exam_data' => [
                'category' => 'five_elements',
                'questions' => [
                    [
                        'hanja_char_id' => 1,
                        'has_char' => false,
                        'char_value' => '',
                        'reading_ko' => '',
                        'meaning_ko' => '목',
                        'element' => null,
                        'prompt' => '테스트 문제 1',
                        'correct_id' => 0,
                        'choices' => [
                            ['id' => 0, 'text' => '목'],
                            ['id' => 1, 'text' => '화'],
                            ['id' => 2, 'text' => '토'],
                            ['id' => 3, 'text' => '금'],
                        ],
                        'explanation' => null,
                    ],
                    [
                        'hanja_char_id' => 2,
                        'has_char' => false,
                        'char_value' => '',
                        'reading_ko' => '',
                        'meaning_ko' => '수',
                        'element' => null,
                        'prompt' => '테스트 문제 2',
                        'correct_id' => 1,
                        'choices' => [
                            ['id' => 0, 'text' => '금'],
                            ['id' => 1, 'text' => '수'],
                            ['id' => 2, 'text' => '목'],
                            ['id' => 3, 'text' => '화'],
                        ],
                        'explanation' => null,
                    ],
                ],
                'requested_count' => 2,
                'actual_count' => 2,
                'source_size' => 2,
                'started_at' => now()->toISOString(),
            ],
        ])->get(route('exam.play'));

        $response->assertOk();
        $response->assertSee('autoAdvanceTimer', false);
        $response->assertSee('scheduleAutoAdvance(qi)', false);
        $response->assertSee('3000', false);
        $response->assertSee('goTo(qi)', false);
    }

    private function matchesGeneratedExamPayload(
        array $examData,
        string $category,
        int $expectedCount,
        bool $expectsHanjaCharId,
    ): bool {
        if ($examData['category'] !== $category || $examData['actual_count'] !== $expectedCount) {
            return false;
        }

        if (($examData['source_size'] ?? 0) < $expectedCount) {
            return false;
        }

        if (count($examData['questions']) !== $expectedCount) {
            return false;
        }

        $prompts = [];

        foreach ($examData['questions'] as $question) {
            if (($question['has_char'] ?? true) !== false) {
                return false;
            }

            if (count($question['choices'] ?? []) !== 4) {
                return false;
            }

            if ($expectsHanjaCharId && empty($question['hanja_char_id'])) {
                return false;
            }

            if (! $expectsHanjaCharId && ! empty($question['hanja_char_id'])) {
                return false;
            }

            $choiceTexts = array_column($question['choices'], 'text');

            if (count(array_unique($choiceTexts)) !== 4) {
                return false;
            }

            $prompts[] = $question['prompt'] ?? '';
        }

        return count(array_unique($prompts)) === $expectedCount;
    }

    private function seedHanjaCategories(): void
    {
        $this->createPublishedHanjaChars('five_elements', 5);
        $this->createPublishedHanjaChars('heavenly_stems', 10);
        $this->createPublishedHanjaChars('earthly_branches', 12);
    }

    private function createProblematicHeavenlyStems(): void
    {
        $this->createCustomHanjaChars([
            ['char_value' => '甲', 'slug' => 'gap', 'reading_ko' => '갑', 'meaning_ko' => '갑옷, 시작의 갑', 'element' => 'wood', 'yin_yang' => 'yang', 'stroke_count' => 5],
            ['char_value' => '乙', 'slug' => 'eul', 'reading_ko' => '을', 'meaning_ko' => '굽은 새싹의 을', 'element' => 'wood', 'yin_yang' => 'yin', 'stroke_count' => 1],
            ['char_value' => '丙', 'slug' => 'byeong', 'reading_ko' => '병', 'meaning_ko' => '밝게 드러나는 병', 'element' => 'fire', 'yin_yang' => 'yang', 'stroke_count' => 5],
            ['char_value' => '丁', 'slug' => 'jeong', 'reading_ko' => '정', 'meaning_ko' => '촛불의 정', 'element' => 'fire', 'yin_yang' => 'yin', 'stroke_count' => 2],
            ['char_value' => '戊', 'slug' => 'mu', 'reading_ko' => '무', 'meaning_ko' => '큰 흙의 무', 'element' => 'earth', 'yin_yang' => 'yang', 'stroke_count' => 5],
            ['char_value' => '己', 'slug' => 'gi', 'reading_ko' => '기', 'meaning_ko' => '몸을 굽힌 기', 'element' => 'earth', 'yin_yang' => 'yin', 'stroke_count' => 3],
            ['char_value' => '庚', 'slug' => 'gyeong', 'reading_ko' => '경', 'meaning_ko' => '날을 세우는 경', 'element' => 'metal', 'yin_yang' => 'yang', 'stroke_count' => 8],
            ['char_value' => '辛', 'slug' => 'sin', 'reading_ko' => '신', 'meaning_ko' => '보석 같은 신', 'element' => 'metal', 'yin_yang' => 'yin', 'stroke_count' => 7],
            ['char_value' => '壬', 'slug' => 'im', 'reading_ko' => '임', 'meaning_ko' => '큰 물의 임', 'element' => 'water', 'yin_yang' => 'yang', 'stroke_count' => 4],
            ['char_value' => '癸', 'slug' => 'gye', 'reading_ko' => '계', 'meaning_ko' => '비나 이슬의 계', 'element' => 'water', 'yin_yang' => 'yin', 'stroke_count' => 9],
        ]);
    }

    private function createAmbiguousHeavenlyStems(): void
    {
        $this->createCustomHanjaChars([
            ['char_value' => '甲', 'slug' => 'gap-dup', 'reading_ko' => '갑', 'meaning_ko' => '큰 물의 갑', 'element' => 'wood', 'yin_yang' => 'yang', 'stroke_count' => 5],
            ['char_value' => '乙', 'slug' => 'eul-dup', 'reading_ko' => '을', 'meaning_ko' => '큰 물의 을', 'element' => 'wood', 'yin_yang' => 'yin', 'stroke_count' => 1],
            ['char_value' => '丙', 'slug' => 'byeong-dup', 'reading_ko' => '병', 'meaning_ko' => '밝은 불의 병', 'element' => 'fire', 'yin_yang' => 'yang', 'stroke_count' => 5],
            ['char_value' => '丁', 'slug' => 'jeong-dup', 'reading_ko' => '정', 'meaning_ko' => '깊은 흙의 정', 'element' => 'earth', 'yin_yang' => 'yin', 'stroke_count' => 2],
        ]);
    }

    private function createPublishedHanjaChars(string $category, int $count): void
    {
        $elements = ['wood', 'fire', 'earth', 'metal', 'water'];
        $yinYang = ['yang', 'yin', 'neutral'];

        foreach (range(1, $count) as $index) {
            HanjaChar::create([
                'char_value' => strtoupper(substr($category, 0, 2)).$index,
                'slug' => strtolower($category).'-'.$index,
                'reading_ko' => 'reading_'.$category.'_'.$index,
                'meaning_ko' => 'meaning '.$category.' '.$index,
                'category' => $category,
                'element' => $elements[($index - 1) % count($elements)],
                'yin_yang' => $yinYang[($index - 1) % count($yinYang)],
                'structure_note' => 'test structure',
                'mnemonic_text' => 'test mnemonic',
                'usage_in_saju' => 'test usage',
                'stroke_count' => 3 + $index,
                'is_core' => true,
                'publish_status' => 'published',
                'published_at' => now(),
            ]);
        }
    }

    private function createCustomHanjaChars(array $chars, string $category = 'heavenly_stems'): void
    {
        foreach ($chars as $char) {
            HanjaChar::create([
                'char_value' => $char['char_value'],
                'slug' => $char['slug'],
                'reading_ko' => $char['reading_ko'],
                'meaning_ko' => $char['meaning_ko'],
                'category' => $category,
                'element' => $char['element'],
                'yin_yang' => $char['yin_yang'],
                'structure_note' => 'test structure',
                'mnemonic_text' => 'test mnemonic',
                'usage_in_saju' => 'test usage',
                'stroke_count' => $char['stroke_count'],
                'is_core' => true,
                'publish_status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}

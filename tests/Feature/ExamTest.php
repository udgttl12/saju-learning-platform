<?php

namespace Tests\Feature;

use App\Models\HanjaChar;
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
}

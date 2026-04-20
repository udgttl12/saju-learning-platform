<?php

namespace Tests\Feature;

use App\Models\HanjaChar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_index_uses_default_count_options_and_small_category_fallback(): void
    {
        $this->createPublishedHanjaChars('five_elements', 5);

        $response = $this->get(route('exam.index'));
        $countOptions = $response->viewData('countOptions');

        $response->assertOk();
        $this->assertSame([5, 10, 20, 50, 100], $countOptions);
    }

    public function test_exam_index_prefers_twenty_as_default_count(): void
    {
        $response = $this->get(route('exam.index'));

        $response->assertOk();
        $response->assertSee('chosenCount: 20', false);
        $response->assertSee('options.includes(20)', false);
    }

    public function test_exam_index_uses_generated_pool_counts_for_dynamic_categories(): void
    {
        $response = $this->get(route('exam.index'));
        $categories = $response->viewData('categories');

        $response->assertOk();
        $this->assertGreaterThan(100, $categories['twelve_shinsal']['count']);
        $this->assertGreaterThan(100, $categories['yukchin']['count']);
    }

    public function test_exam_can_start_with_available_default_count(): void
    {
        $this->createPublishedHanjaChars('earthly_branches', 12);

        $response = $this->post(route('exam.start'), [
            'category' => 'earthly_branches',
            'count' => 10,
        ]);

        $response->assertRedirect(route('exam.play'));
        $response->assertSessionHas('exam_data', function (array $examData): bool {
            return $examData['category'] === 'earthly_branches'
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
        $response->assertSessionHas('exam_data', fn (array $examData): bool => $this->matchesGeneratedExamPayload($examData, 'yukchin', 20));
    }

    public function test_generated_twelve_shinsal_exam_can_start_without_seeded_quiz_items(): void
    {
        $response = $this->post(route('exam.start'), [
            'category' => 'twelve_shinsal',
            'count' => 20,
        ]);

        $response->assertRedirect(route('exam.play'));
        $response->assertSessionHas('exam_data', fn (array $examData): bool => $this->matchesGeneratedExamPayload($examData, 'twelve_shinsal', 20));
    }

    private function matchesGeneratedExamPayload(array $examData, string $category, int $expectedCount): bool
    {
        if ($examData['category'] !== $category || $examData['actual_count'] !== $expectedCount) {
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

            $prompts[] = $question['prompt'] ?? '';
        }

        return count(array_unique($prompts)) === $expectedCount;
    }

    private function createPublishedHanjaChars(string $category, int $count): void
    {
        foreach (range(1, $count) as $index) {
            HanjaChar::create([
                'char_value' => 'B'.$index,
                'slug' => strtolower($category).'-'.$index,
                'reading_ko' => 'reading'.$index,
                'meaning_ko' => 'meaning '.$index,
                'category' => $category,
                'element' => 'wood',
                'yin_yang' => 'yang',
                'structure_note' => 'test structure',
                'mnemonic_text' => 'test mnemonic',
                'usage_in_saju' => 'test usage',
                'stroke_count' => 3,
                'is_core' => true,
                'publish_status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}

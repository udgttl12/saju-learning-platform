<?php

namespace Tests\Feature;

use App\Models\HanjaChar;
use App\Models\LearningTrack;
use App\Models\Lesson;
use App\Models\LessonStep;
use Database\Seeders\HanjaCharSeeder;
use Database\Seeders\HanjaGroupSeeder;
use Database\Seeders\LearningTrackSeeder;
use Database\Seeders\LessonSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonPracticeCanvasTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_stroke_flow_seed_has_a_practice_hanja_for_canvas(): void
    {
        $this->seed([
            UserSeeder::class,
            LearningTrackSeeder::class,
            LessonSeeder::class,
            HanjaGroupSeeder::class,
            HanjaCharSeeder::class,
        ]);

        $lesson = Lesson::where('slug', 'basic-stroke-flow')
            ->with('hanjaChars')
            ->firstOrFail();

        $this->assertSame('木', $lesson->hanjaChars->first()?->char_value);
        $this->assertSame(1, $lesson->hanjaChars->count());
    }

    public function test_guided_practice_step_renders_canvas_when_hanja_is_linked(): void
    {
        $track = LearningTrack::factory()->create([
            'unlock_rule_json' => ['requires' => []],
        ]);
        $lesson = Lesson::factory()->create([
            'learning_track_id' => $track->id,
            'slug' => 'linked-practice-lesson',
            'code' => 'LES_LINKED_PRACTICE',
            'unlock_rule_json' => ['requires' => []],
            'lesson_type' => 'practice',
            'publish_status' => 'published',
        ]);
        LessonStep::create([
            'lesson_id' => $lesson->id,
            'step_type' => 'guided_practice',
            'title' => '가이드 위에 써보기',
            'content_markdown' => '희미한 가이드 선을 따라 써보세요.',
            'payload_json' => ['repeat_count' => 3],
            'sort_order' => 1,
            'is_required' => true,
            'estimated_minutes' => 5,
        ]);
        $hanja = HanjaChar::factory()->create([
            'char_value' => '木',
            'slug' => 'mok-test',
            'reading_ko' => '목',
            'meaning_ko' => '나무',
        ]);

        $lesson->hanjaChars()->attach($hanja->id, [
            'relation_type' => 'primary',
            'sort_order' => 1,
        ]);

        $this->get(route('lessons.show', $lesson->slug))
            ->assertOk()
            ->assertSee('아래 캔버스에서 3번 연습해보세요!')
            ->assertSee('가이드 글자')
            ->assertSee('lessonCell', false)
            ->assertDontSee('연습 글자가 연결되지 않았습니다');
    }

    public function test_guided_practice_step_shows_empty_state_without_hanja_link(): void
    {
        $track = LearningTrack::factory()->create([
            'unlock_rule_json' => ['requires' => []],
        ]);
        $lesson = Lesson::factory()->create([
            'learning_track_id' => $track->id,
            'slug' => 'missing-practice-hanja',
            'code' => 'LES_MISSING_PRACTICE_HANJA',
            'unlock_rule_json' => ['requires' => []],
            'lesson_type' => 'practice',
            'publish_status' => 'published',
        ]);
        LessonStep::create([
            'lesson_id' => $lesson->id,
            'step_type' => 'guided_practice',
            'title' => '가이드 위에 써보기',
            'content_markdown' => '희미한 가이드 선을 따라 써보세요.',
            'payload_json' => ['repeat_count' => 3],
            'sort_order' => 1,
            'is_required' => true,
            'estimated_minutes' => 5,
        ]);

        $this->get(route('lessons.show', $lesson->slug))
            ->assertOk()
            ->assertSee('연습 글자가 연결되지 않았습니다')
            ->assertDontSee('아래 캔버스에서 3번 연습해보세요!')
            ->assertDontSee('lessonCell', false);
    }
}

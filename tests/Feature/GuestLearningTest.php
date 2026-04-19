<?php

namespace Tests\Feature;

use App\Models\LearningTrack;
use App\Models\Lesson;
use App\Models\LessonStep;
use App\Models\Profile;
use App\Models\QuizItem;
use App\Models\QuizSet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestLearningTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_complete_a_lesson_and_unlock_the_next_lesson(): void
    {
        $track = $this->createTrack([
            'code' => 'TRK_GUEST_001',
            'slug' => 'guest-track-001',
        ]);
        $firstLesson = $this->createLesson($track, [
            'code' => 'LES_GUEST_001',
            'slug' => 'guest-lesson-001',
            'sort_order' => 1,
            'unlock_rule_json' => ['requires' => []],
        ]);
        $secondLesson = $this->createLesson($track, [
            'code' => 'LES_GUEST_002',
            'slug' => 'guest-lesson-002',
            'sort_order' => 2,
            'unlock_rule_json' => ['requires' => [$firstLesson->code]],
        ]);

        $this->addIntroStep($firstLesson);
        $this->addIntroStep($secondLesson);

        $this->get(route('lessons.show', $firstLesson->slug))
            ->assertOk();

        $this->post(route('lessons.complete', $firstLesson->slug))
            ->assertRedirect(route('tracks.show', $track->slug))
            ->assertSessionHas('success');

        $this->get(route('lessons.show', $secondLesson->slug))
            ->assertOk();

        $this->assertContains($firstLesson->code, session('guest_learning.completed_lesson_codes', []));
    }

    public function test_guest_can_submit_a_lesson_quiz_without_creating_database_attempts(): void
    {
        $track = $this->createTrack([
            'code' => 'TRK_GUEST_QUIZ',
            'slug' => 'guest-track-quiz',
        ]);
        $lesson = $this->createLesson($track, [
            'code' => 'LES_GUEST_QUIZ',
            'slug' => 'guest-lesson-quiz',
        ]);
        $quizSet = QuizSet::create([
            'lesson_id' => $lesson->id,
            'code' => 'QZ_GUEST_001',
            'title' => '게스트 퀴즈',
            'scope_type' => 'lesson',
            'description' => '비회원 체험용 퀴즈',
            'difficulty_level' => 1,
            'pass_score' => 70,
            'publish_status' => 'published',
            'published_at' => now(),
        ]);

        $this->addIntroStep($lesson);
        LessonStep::create([
            'lesson_id' => $lesson->id,
            'step_type' => 'quiz',
            'title' => '퀴즈',
            'content_markdown' => '퀴즈를 풀어보세요.',
            'payload_json' => ['quiz_set_code' => $quizSet->code],
            'sort_order' => 2,
            'is_required' => true,
            'estimated_minutes' => 3,
        ]);

        $item = QuizItem::create([
            'quiz_set_id' => $quizSet->id,
            'question_type' => 'multiple_choice',
            'source_type' => 'lesson',
            'prompt_text' => '목(木)에 해당하는 것은?',
            'target_hanja_char_id' => null,
            'concept_key' => 'wood',
            'choices_json' => ['목', '화'],
            'answer_payload_json' => ['correct_choice_index' => 0],
            'meta_json' => ['review_title' => '오행'],
            'explanation_text' => '목은 성장의 기운을 뜻해요.',
            'hint_text' => null,
            'sort_order' => 1,
            'points' => 10,
        ]);

        $this->get(route('quiz.show', $quizSet->code))
            ->assertOk();

        $this->post(route('quiz.submit', $quizSet->code), [
            'answers' => [
                $item->id => 0,
            ],
        ])->assertRedirect(route('quiz.result', $quizSet->code))
            ->assertSessionHas("quiz_result_{$quizSet->code}", function (array $data): bool {
                return ($data['is_guest'] ?? false) === true
                    && ($data['score']['percentage'] ?? null) === 100;
            });

        $this->assertDatabaseCount('quiz_attempts', 0);
    }

    public function test_guest_login_creates_a_temporary_user_and_profile(): void
    {
        $response = $this->post(route('guest.login'), [
            'redirect_to' => '/tracks',
        ]);

        $response->assertRedirect('/tracks');
        $this->assertAuthenticated();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('profiles', 1);

        $user = User::first();
        $profile = Profile::first();

        $this->assertStringEndsWith('@guest.local', $user->email);
        $this->assertNotNull($profile->onboarding_completed_at);
    }

    private function createTrack(array $attributes = []): LearningTrack
    {
        return LearningTrack::factory()->create(array_merge([
            'title' => '게스트 트랙',
            'short_description' => '비회원 학습 테스트용 트랙',
            'unlock_rule_json' => null,
        ], $attributes));
    }

    private function createLesson(LearningTrack $track, array $attributes = []): Lesson
    {
        return Lesson::factory()->create(array_merge([
            'learning_track_id' => $track->id,
            'title' => '게스트 레슨',
            'objective' => '게스트 학습 흐름 확인',
            'publish_status' => 'published',
            'published_at' => now(),
        ], $attributes));
    }

    private function addIntroStep(Lesson $lesson): void
    {
        LessonStep::create([
            'lesson_id' => $lesson->id,
            'step_type' => 'intro',
            'title' => '도입',
            'content_markdown' => '레슨 소개',
            'payload_json' => null,
            'sort_order' => 1,
            'is_required' => true,
            'estimated_minutes' => 2,
        ]);
    }
}

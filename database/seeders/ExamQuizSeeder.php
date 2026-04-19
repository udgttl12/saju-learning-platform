<?php

namespace Database\Seeders;

use App\Services\YukchinQuestionGeneratorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamQuizSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $trackIds = DB::table('learning_tracks')
            ->whereIn('code', ['TRACK_TWELVE_SHINSAL', 'TRACK_YUKCHIN'])
            ->pluck('id', 'code');

        $shinsalSetId = DB::table('quiz_sets')->insertGetId([
            'lesson_id' => null,
            'learning_track_id' => $trackIds['TRACK_TWELVE_SHINSAL'] ?? null,
            'code' => 'EXAM_TWELVE_SHINSAL',
            'title' => '12신살 트랙 시험',
            'scope_type' => 'track',
            'description' => '12신살의 순서, 핵심 신살, 해석 원리를 종합 점검하는 트랙 시험',
            'difficulty_level' => 3,
            'pass_score' => 80,
            'publish_status' => 'published',
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $yukchinSetId = DB::table('quiz_sets')->insertGetId([
            'lesson_id' => null,
            'learning_track_id' => $trackIds['TRACK_YUKCHIN'] ?? null,
            'code' => 'EXAM_YUKCHIN',
            'title' => '육친론(십성) 트랙 시험',
            'scope_type' => 'track',
            'description' => '십성의 관계, 남녀 해석 차이, 오행/음양 분기를 종합 점검하는 트랙 시험',
            'difficulty_level' => 3,
            'pass_score' => 80,
            'publish_status' => 'published',
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $shinsalItems = [
            $this->mc('12신살의 고정된 순서에서 첫 번째에 오는 신살은?', ['겁살', '재살', '천살', '지살'], 0, '12신살 순서는 겁·재·천·지·년·월·망·장·반·역·육·화입니다.', 'shinsal.order', '12신살의 순서', 'LESSON_SHINSAL_001'),
            $this->mc('삼합의 왕지(旺支)에 해당하는 신살은?', ['지살', '화개살', '장성살', '역마살'], 2, '장성살은 삼합의 왕지에 위치합니다.', 'shinsal.anchor', '12신살 결정 원리', 'LESSON_SHINSAL_001'),
            $this->mc('삼합의 생지(生支)에 해당하며 이동·활동을 상징하는 신살은?', ['역마살', '도화살', '장성살', '반안살'], 0, '역마살은 생지에 해당하며 이동성과 활동성을 상징합니다.', 'shinsal.big4', '4대 핵심 신살', 'LESSON_SHINSAL_002'),
            $this->mc('학문·예술·종교적 깊이와 고독을 상징하는 신살은?', ['망신살', '화개살', '재살', '월살'], 1, '화개살은 학문·예술·고독을 상징합니다.', 'shinsal.big4', '4대 핵심 신살', 'LESSON_SHINSAL_002'),
            $this->mc('사주 주인의 일지가 "午"일 때 속한 삼합은?', ['수국(申子辰)', '목국(亥卯未)', '화국(寅午戌)', '금국(巳酉丑)'], 2, '午는 화국(寅午戌)의 왕지입니다.', 'shinsal.anchor', '12신살 결정 원리', 'LESSON_SHINSAL_001'),
            $this->mc('승진·명예·조직 내 상승을 상징하는 신살은?', ['재살', '반안살', '월살', '육해살'], 1, '반안살은 조직 안에서 지위를 올리는 성향과 연결됩니다.', 'shinsal.other-eight', '나머지 8대 신살', 'LESSON_SHINSAL_003'),
            $this->mc('질병·방해·돌봄과 연결되며 의료·복지 직업에 유리한 신살은?', ['천살', '월살', '육해살', '재살'], 2, '육해살은 질병·방해와 연결되어 보건/복지 해석과 자주 이어집니다.', 'shinsal.other-eight', '나머지 8대 신살', 'LESSON_SHINSAL_003'),
            $this->mc('신살 해석 시 일반적으로 가장 먼저 보는 것은?', ['신살', '십신', '합충', '원국과 대운'], 3, '신살은 가장 마지막 양념으로 봐야 합니다.', 'shinsal.interpretation', '신살 해석 순서', 'LESSON_SHINSAL_004'),
            $this->mc('체면 손상·노출·공개적 구설이 특징이지만 현대에는 노출 자산형 직업과도 연결되는 신살은?', ['망신살', '천살', '지살', '겁살'], 0, '망신살은 공개 노출과 연결되어 현대엔 방송/크리에이터 해석에도 자주 씁니다.', 'shinsal.other-eight', '나머지 8대 신살', 'LESSON_SHINSAL_003'),
            $this->mc('12신살 중 순서상 월살 다음에 오는 신살은?', ['장성살', '망신살', '반안살', '역마살'], 1, '월살 다음은 망신살입니다.', 'shinsal.order', '12신살의 순서', 'LESSON_SHINSAL_001'),
            $this->mc('관재구설·법적 분쟁·사건사고와 가장 밀접한 신살은?', ['재살', '지살', '반안살', '년살'], 0, '재살은 재난과 관재구설의 성격을 가집니다.', 'shinsal.other-eight', '나머지 8대 신살', 'LESSON_SHINSAL_003'),
            $this->mc('운에서 들어올 때 이사·이직·해외 이동 가능성을 가장 강하게 떠올리게 하는 신살은?', ['역마살', '반안살', '화개살', '월살'], 0, '역마살은 이동과 환경 변화에 가장 직접적으로 연결됩니다.', 'shinsal.big4', '4대 핵심 신살', 'LESSON_SHINSAL_002'),
            $this->tf('12신살은 일지 또는 연지의 삼합을 기준으로 읽는다.', true, '12신살은 일지/연지의 삼합을 기준으로 배치합니다.', 'shinsal.anchor', '12신살 결정 원리', 'LESSON_SHINSAL_001'),
            $this->tf('도화살과 장성살은 전혀 다른 자리에서 나온다.', false, '왕지 자리에서 도화/장성의 해석 방향이 갈리는 경우가 많습니다.', 'shinsal.big4', '4대 핵심 신살', 'LESSON_SHINSAL_002'),
            $this->short('12신살을 외울 때 많이 쓰는 첫 네 글자는?', ['겁재천지', '겁·재·천·지', '겁재천지살'], '12신살의 시작은 겁·재·천·지입니다.', 'shinsal.order', '12신살의 순서', 'LESSON_SHINSAL_001'),
        ];

        $this->insertItems($shinsalSetId, $shinsalItems, $now);

        $generator = app(YukchinQuestionGeneratorService::class);

        $yukchinItems = [
            $this->mc('일간과 오행이 같고 음양도 같은 십성은?', ['비견', '겁재', '식신', '정재'], 0, '같은 오행, 같은 음양은 비견입니다.', 'yukchin.definition', '육친과 십성의 기본', 'LESSON_YUKCHIN_001'),
            $this->mc('일간이 극(剋)하는 오행이며 음양이 다를 때의 십성은?', ['편재', '정재', '편관', '정관'], 1, '내가 극하는 오행 중 음양이 다르면 정재입니다.', 'yukchin.jaegwan', '재성·관성', 'LESSON_YUKCHIN_004'),
            $this->mc('남자 사주에서 "아내"를 상징하는 십성은?', ['정인', '정관', '정재', '식신'], 2, '남자에게 정재는 아내를 상징합니다.', 'yukchin.gender', '성별 해석 차이', 'LESSON_YUKCHIN_001'),
            $this->mc('여자 사주에서 "남편"을 상징하는 십성은?', ['정재', '정관', '식신', '정인'], 1, '여자에게 정관은 남편을 상징합니다.', 'yukchin.gender', '성별 해석 차이', 'LESSON_YUKCHIN_001'),
            $this->mc('여자 사주에서 "자식"에 해당하는 십성은?', ['식상(식신·상관)', '관성(편관·정관)', '재성(편재·정재)', '인성(편인·정인)'], 0, '여자에게 자식은 내가 낳는 식상으로 봅니다.', 'yukchin.siksang', '식신·상관', 'LESSON_YUKCHIN_003'),
            $this->mc('비견과 겁재를 합쳐 부르는 말은?', ['식상', '비겁', '재관', '인성'], 1, '비견+겁재를 비겁이라 부릅니다.', 'yukchin.bigyeop', '비견·겁재', 'LESSON_YUKCHIN_002'),
            $this->mc('"내가 관(官)을 상(傷)하게 한다"는 뜻에서 이름 붙은 십성은?', ['식신', '상관', '겁재', '편관'], 1, '상관은 관을 상하게 한다는 뜻의 십성입니다.', 'yukchin.siksang', '식신·상관', 'LESSON_YUKCHIN_003'),
            $this->mc('정재와 편재의 차이를 가장 잘 설명한 것은?', ['정재=애인, 편재=아내', '정재=큰 흐름의 돈, 편재=월급', '정재=월급·꾸준함, 편재=큰 흐름·사업', '정재=남편, 편재=시어머니'], 2, '정재는 안정적 수입, 편재는 큰 흐름과 사업 감각에 가깝습니다.', 'yukchin.jaegwan', '재성·관성', 'LESSON_YUKCHIN_004'),
            $this->mc('"칠살(七殺)"이라고도 부르는 십성은?', ['정관', '편관', '편인', '겁재'], 1, '편관은 칠살이라고도 부릅니다.', 'yukchin.jaegwan', '재성·관성', 'LESSON_YUKCHIN_004'),
            $this->mc('사주에서 어머니와 정통 학문·자격증을 상징하는 십성은?', ['편인', '정인', '정관', '식신'], 1, '정인은 어머니, 정통 학문, 자격증과 연결됩니다.', 'yukchin.insung', '인성', 'LESSON_YUKCHIN_005'),
            $this->mc('비견이 사주에 너무 많을 때 흔들리기 쉬운 십성은?', ['재성(편재·정재)', '인성(편인·정인)', '관성(편관·정관)', '식상(식신·상관)'], 0, '비겁이 많으면 재성을 압박하기 쉽습니다.', 'yukchin.bigyeop', '비견·겁재', 'LESSON_YUKCHIN_002'),
            $this->mc('"여유·낙천·미식·서비스업 적성"과 가장 가까운 십성은?', ['식신', '상관', '편관', '겁재'], 0, '식신은 여유, 먹을 복, 표현 감각과 연결됩니다.', 'yukchin.siksang', '식신·상관', 'LESSON_YUKCHIN_003'),
            array_merge(
                $generator->generateMultipleChoice('甲', '辛'),
                ['review_title' => '일간 기준 십성 판별', 'review_lesson_code' => 'LESSON_YUKCHIN_001']
            ),
            array_merge(
                $generator->generateMultipleChoice('丁', '壬'),
                ['review_title' => '일간 기준 십성 판별', 'review_lesson_code' => 'LESSON_YUKCHIN_001']
            ),
            array_merge(
                $generator->generateMultipleChoice('庚', '乙'),
                ['review_title' => '일간 기준 십성 판별', 'review_lesson_code' => 'LESSON_YUKCHIN_001']
            ),
        ];

        $this->insertItems($yukchinSetId, $yukchinItems, $now);
    }

    private function insertItems(int $quizSetId, array $items, $now): void
    {
        $rows = [];

        foreach ($items as $index => $item) {
            $rows[] = [
                'quiz_set_id' => $quizSetId,
                'question_type' => $item['question_type'],
                'source_type' => $item['source_type'] ?? 'manual',
                'prompt_text' => $item['prompt_text'],
                'target_hanja_char_id' => null,
                'concept_key' => $item['concept_key'],
                'choices_json' => isset($item['choices_json'])
                    ? json_encode($item['choices_json'], JSON_UNESCAPED_UNICODE)
                    : null,
                'answer_payload_json' => json_encode($item['answer_payload_json'], JSON_UNESCAPED_UNICODE),
                'meta_json' => json_encode([
                    'review_title' => $item['review_title'],
                    'review_lesson_code' => $item['review_lesson_code'],
                    'review_prompt' => $item['prompt_text'],
                    'weak_label' => $item['review_title'],
                ], JSON_UNESCAPED_UNICODE),
                'explanation_text' => $item['explanation_text'],
                'hint_text' => $item['hint_text'] ?? null,
                'sort_order' => $index + 1,
                'points' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('quiz_items')->insert($rows);
    }

    private function mc(string $prompt, array $choices, int $correct, string $explanation, string $conceptKey, string $reviewTitle, string $reviewLessonCode): array
    {
        return [
            'question_type' => 'multiple_choice',
            'prompt_text' => $prompt,
            'choices_json' => $choices,
            'answer_payload_json' => ['correct_choice_index' => $correct],
            'explanation_text' => $explanation,
            'concept_key' => $conceptKey,
            'review_title' => $reviewTitle,
            'review_lesson_code' => $reviewLessonCode,
        ];
    }

    private function tf(string $prompt, bool $correct, string $explanation, string $conceptKey, string $reviewTitle, string $reviewLessonCode): array
    {
        return [
            'question_type' => 'true_false',
            'prompt_text' => $prompt,
            'answer_payload_json' => ['correct_boolean' => $correct],
            'explanation_text' => $explanation,
            'concept_key' => $conceptKey,
            'review_title' => $reviewTitle,
            'review_lesson_code' => $reviewLessonCode,
        ];
    }

    private function short(string $prompt, array $acceptedAnswers, string $explanation, string $conceptKey, string $reviewTitle, string $reviewLessonCode): array
    {
        return [
            'question_type' => 'short_answer',
            'prompt_text' => $prompt,
            'answer_payload_json' => [
                'correct_answer' => $acceptedAnswers[0],
                'accepted_answers' => $acceptedAnswers,
            ],
            'explanation_text' => $explanation,
            'concept_key' => $conceptKey,
            'review_title' => $reviewTitle,
            'review_lesson_code' => $reviewLessonCode,
        ];
    }
}

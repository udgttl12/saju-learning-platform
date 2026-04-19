<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EarthBranchAdvancedTrackSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $trackId = DB::table('learning_tracks')->insertGetId([
            'code' => 'TRACK_EARTH_BRANCH_ADVANCED',
            'slug' => 'earth-branch-advanced',
            'title' => '지지 확장 이해',
            'short_description' => '지지의 음양, 계절, 운동, 현실 계절 연결까지 입체적으로 익히는 트랙',
            'target_audience' => 'adult_hobby_beginner',
            'difficulty_level' => 3,
            'estimated_total_minutes' => 95,
            'sort_order' => 5,
            'unlock_rule_json' => json_encode([
                'requires' => [
                    ['type' => 'track_completed', 'code' => 'TRACK_EARTHLY_BRANCHES'],
                ],
            ], JSON_UNESCAPED_UNICODE),
            'publish_status' => 'published',
            'published_at' => $now,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $lessons = [
            [
                'code' => 'LESSON_BRANCH_ADV_001',
                'slug' => 'branch-yinyang',
                'title' => '지지의 음양',
                'objective' => '지지 12개가 음과 양으로 교차하는 리듬이라는 점을 이해한다.',
                'summary' => '자·인·진·오·신·술은 양, 축·묘·사·미·유·해는 음',
                'estimated_minutes' => 16,
                'requires' => [],
                'steps' => [
                    ['type' => 'intro', 'title' => '지지는 리듬이다', 'content' => "지지 12개는 단순히 동물 이름표가 아닙니다.\n양과 음이 번갈아 흐르면서 계절과 운동의 리듬을 만듭니다."],
                    ['type' => 'explanation', 'title' => '양 지지와 음 지지', 'content' => "양 지지: 子, 寅, 辰, 午, 申, 戌\n음 지지: 丑, 卯, 巳, 未, 酉, 亥\n\n지지는 한 칸씩 넘어갈 때마다 음과 양이 교차합니다."],
                    ['type' => 'explanation', 'title' => '왜 음양이 중요한가', 'content' => "같은 겨울이라도 子는 차갑지만 움직이려는 양의 수이고, 亥는 깊이 스며드는 음의 수입니다.\n이 차이를 느끼기 시작하면 지지가 캐릭터처럼 보입니다."],
                    ['type' => 'quiz', 'title' => '즉시 확인', 'content' => '방금 배운 음양 구분을 짧게 점검해봅시다.', 'payload' => ['quiz_set_code' => 'QUIZ_BRANCH_ADV_001']],
                    ['type' => 'summary', 'title' => '핵심 정리', 'content' => "지지는 음과 양이 번갈아 흐르는 구조입니다.\n같은 계열의 지지라도 음양에 따라 질감이 달라집니다.", 'payload' => ['keywords' => ['지지', '음양', '리듬']]],
                ],
                'quiz_items' => [
                    $this->mc('묘(卯)는 음과 양 중 어디에 속할까?', ['양', '음', '중성', '계절에 따라 달라진다'], 1, '묘는 음의 지지입니다.', 'branch.yinyang', '지지의 음양'),
                    $this->mc('다음 중 양의 지지끼리만 묶인 것은?', ['축·묘·유', '해·미·사', '자·오·신', '묘·사·해'], 2, '자, 오, 신은 모두 양의 지지입니다.', 'branch.yinyang', '지지의 음양'),
                    $this->tf('유(酉)는 음의 지지다.', true, '유는 음의 지지입니다.', 'branch.yinyang', '지지의 음양'),
                    $this->tf('해(亥)는 양의 지지다.', false, '해는 음의 지지입니다.', 'branch.yinyang', '지지의 음양'),
                ],
            ],
            [
                'code' => 'LESSON_BRANCH_ADV_002',
                'slug' => 'branch-seasons',
                'title' => '지지와 계절',
                'objective' => '지지 12개가 4계절을 어떻게 나누는지 이해한다.',
                'summary' => '인묘진 봄, 사오미 여름, 신유술 가을, 해자축 겨울',
                'estimated_minutes' => 18,
                'requires' => ['LESSON_BRANCH_ADV_001'],
                'steps' => [
                    ['type' => 'intro', 'title' => '지지는 계절의 언어다', 'content' => '지지는 시간을 12칸으로 나눈 기호이면서 동시에 계절의 흐름을 담은 언어입니다.'],
                    ['type' => 'explanation', 'title' => '사계절 배치', 'content' => "봄: 寅卯辰\n여름: 巳午未\n가을: 申酉戌\n겨울: 亥子丑\n\n각 계절은 시작-한창-마무리의 결을 함께 가집니다."],
                    ['type' => 'explanation', 'title' => '계절 안에서도 결이 다르다', 'content' => '봄이라고 해도 寅은 막 싹이 트는 시작, 卯는 가장 활짝 펼쳐진 한가운데, 辰은 다음 계절을 준비하는 마감입니다.'],
                    ['type' => 'quiz', 'title' => '즉시 확인', 'content' => '계절 배치를 바로 손에 붙여봅시다.', 'payload' => ['quiz_set_code' => 'QUIZ_BRANCH_ADV_002']],
                    ['type' => 'summary', 'title' => '핵심 정리', 'content' => "지지의 계절은 세 글자씩 끊어 읽습니다.\n각 묶음은 시작-절정-정리의 결을 함께 품습니다.", 'payload' => ['keywords' => ['계절', '인묘진', '해자축']]],
                ],
                'quiz_items' => [
                    $this->mc('해자축은 어느 계절에 해당할까?', ['봄', '여름', '가을', '겨울'], 3, '해자축은 겨울입니다.', 'branch.season', '지지와 계절'),
                    $this->mc('다음 중 가을에 해당하는 지지 묶음은?', ['사오미', '신유술', '해자축', '인묘진'], 1, '신유술이 가을입니다.', 'branch.season', '지지와 계절'),
                    $this->tf('인묘진은 봄의 흐름을 이룬다.', true, '인묘진은 봄입니다.', 'branch.season', '지지와 계절'),
                    $this->tf('사오미는 겨울의 지지다.', false, '사오미는 여름의 지지입니다.', 'branch.season', '지지와 계절'),
                ],
            ],
            [
                'code' => 'LESSON_BRANCH_ADV_003',
                'slug' => 'branch-life-cycle',
                'title' => '지지의 운동: 생장수장',
                'objective' => '지지를 생장수장의 운동감으로 읽는 감각을 익힌다.',
                'summary' => '생-장-수-장의 운동감으로 계절을 읽는다',
                'estimated_minutes' => 18,
                'requires' => ['LESSON_BRANCH_ADV_002'],
                'steps' => [
                    ['type' => 'intro', 'title' => '계절은 멈춘 표가 아니다', 'content' => '지지의 계절은 정지된 사진이 아니라 움직이는 영화처럼 읽는 것이 좋습니다.'],
                    ['type' => 'explanation', 'title' => '생장수장 기본 감각', 'content' => "생(生): 막 트기 시작함\n장(長): 활짝 자라남\n수(收): 거두고 정리함\n장(藏): 저장하고 품음\n\n봄은 생, 여름은 장, 가을은 수, 겨울은 장의 결이 강합니다."],
                    ['type' => 'explanation', 'title' => '계절과 운동 연결', 'content' => '인묘진은 봄의 생, 사오미는 여름의 장, 신유술은 가을의 수, 해자축은 겨울의 장으로 읽으면 흐름이 살아납니다.'],
                    ['type' => 'quiz', 'title' => '즉시 확인', 'content' => '계절과 운동을 연결해보세요.', 'payload' => ['quiz_set_code' => 'QUIZ_BRANCH_ADV_003']],
                    ['type' => 'summary', 'title' => '핵심 정리', 'content' => "지지는 계절만이 아니라 에너지의 운동을 함께 보여줍니다.\n생장수장을 붙여 읽으면 암기가 아니라 흐름이 됩니다.", 'payload' => ['keywords' => ['생장수장', '운동', '흐름']]],
                ],
                'quiz_items' => [
                    $this->mc('생장수장에서 겨울에 가장 가까운 운동은?', ['생', '장', '수', '장(藏)'], 3, '겨울은 저장하는 장(藏)의 성격이 강합니다.', 'branch.cycle', '지지의 운동'),
                    $this->mc('가을의 운동감을 가장 잘 설명한 것은?', ['싹이 튼다', '활짝 퍼진다', '거두고 정리한다', '깊이 저장한다'], 2, '가을은 수(收), 거두고 정리하는 흐름입니다.', 'branch.cycle', '지지의 운동'),
                    $this->tf('여름은 장(長), 가장 왕성하게 드러나는 흐름에 가깝다.', true, '여름은 장(長)의 계절입니다.', 'branch.cycle', '지지의 운동'),
                    $this->tf('겨울은 수(收)의 흐름이 가장 강하다.', false, '겨울은 장(藏), 저장하는 결이 강합니다.', 'branch.cycle', '지지의 운동'),
                ],
            ],
            [
                'code' => 'LESSON_BRANCH_ADV_004',
                'slug' => 'branch-real-seasons',
                'title' => '지지와 현실 계절 연결',
                'objective' => '지지의 계절을 현실 감각과 연결해 떠올릴 수 있다.',
                'summary' => '현실의 온도, 색, 행동과 지지 계절을 연결한다',
                'estimated_minutes' => 18,
                'requires' => ['LESSON_BRANCH_ADV_003'],
                'steps' => [
                    ['type' => 'intro', 'title' => '현실 감각으로 붙잡기', 'content' => '지지를 오래 기억하려면 책 속 문장보다 현실 감각에 붙이는 것이 좋습니다.'],
                    ['type' => 'explanation', 'title' => '현실 계절 예시', 'content' => "봄(인묘진): 새싹, 출발, 바람이 풀리는 느낌\n여름(사오미): 햇빛, 발산, 열기\n가을(신유술): 수확, 정리, 건조함\n겨울(해자축): 저장, 응축, 깊은 휴식"],
                    ['type' => 'explanation', 'title' => '사주 해석에서의 도움', 'content' => "이 감각이 있으면 차트에서 지지가 반복될 때 분위기를 빠르게 읽을 수 있습니다.\n예를 들어 겨울 지지가 많으면 응축·보존의 톤이 강하다고 느끼기 쉬워집니다."],
                    ['type' => 'quiz', 'title' => '즉시 확인', 'content' => '현실 감각과 계절을 연결해봅시다.', 'payload' => ['quiz_set_code' => 'QUIZ_BRANCH_ADV_004']],
                    ['type' => 'summary', 'title' => '핵심 정리', 'content' => "지지는 현실의 계절 감각과 연결할수록 오래 남습니다.\n눈앞의 장면이 떠오를수록 해석이 빨라집니다.", 'payload' => ['keywords' => ['현실 계절', '이미지', '체감']]],
                ],
                'quiz_items' => [
                    $this->mc('차갑고 응축되며 다음 시작을 준비하는 분위기에 가장 가까운 계절 묶음은?', ['인묘진', '사오미', '신유술', '해자축'], 3, '해자축은 겨울의 응축과 저장에 가깝습니다.', 'branch.realworld', '현실 계절 연결'),
                    $this->mc('수확과 정리의 분위기에 가장 가까운 묶음은?', ['신유술', '사오미', '인묘진', '해자축'], 0, '신유술은 가을, 수확과 정리의 흐름입니다.', 'branch.realworld', '현실 계절 연결'),
                    $this->tf('인묘진은 새싹이 트고 바깥으로 뻗는 이미지와 잘 맞는다.', true, '인묘진은 봄의 이미지와 잘 맞습니다.', 'branch.realworld', '현실 계절 연결'),
                    $this->tf('해자축은 발산과 최고조의 열기를 상징한다.', false, '해자축은 저장과 응축의 흐름에 가깝습니다.', 'branch.realworld', '현실 계절 연결'),
                ],
            ],
            [
                'code' => 'LESSON_BRANCH_ADV_005',
                'slug' => 'branch-advanced-wrapup',
                'title' => '지지 종합 정리',
                'objective' => '지지의 음양, 계절, 운동, 현실 계절 연결을 한 흐름으로 묶는다.',
                'summary' => '지지를 표가 아니라 살아 있는 흐름으로 묶는 종합 레슨',
                'estimated_minutes' => 15,
                'requires' => ['LESSON_BRANCH_ADV_004'],
                'steps' => [
                    ['type' => 'intro', 'title' => '이제 지지를 입체로 본다', 'content' => '지지를 외운다는 것은 12개를 나열하는 것이 아니라 음양-계절-운동을 동시에 붙잡는 것입니다.'],
                    ['type' => 'explanation', 'title' => '한 줄로 묶기', 'content' => "양/음의 리듬 위에 계절이 얹히고, 계절 위에 생장수장의 운동이 얹힙니다.\n그리고 그 위에 현실의 촉감이 덧붙습니다."],
                    ['type' => 'quiz', 'title' => '즉시 확인', 'content' => '종합 복습으로 손에 붙였는지 확인해봅시다.', 'payload' => ['quiz_set_code' => 'QUIZ_BRANCH_ADV_005']],
                    ['type' => 'summary', 'title' => '트랙 마무리', 'content' => "이제 지지를 보면 계절과 운동이 함께 떠오르는 상태가 목표입니다.\n다음 단계에서는 이 감각을 60갑자와 구조 읽기로 연결합니다.", 'payload' => ['keywords' => ['지지 종합', '다음 단계', '60갑자']]],
                ],
                'quiz_items' => [
                    $this->mc('다음 중 지지를 가장 입체적으로 이해한 설명은?', ['12개의 동물 이름이다', '순서만 외우면 된다', '음양, 계절, 운동이 함께 흐르는 구조다', '한자 모양만 기억하면 된다'], 2, '지지는 음양, 계절, 운동이 함께 흐르는 구조입니다.', 'branch.integration', '지지 종합 정리'),
                    $this->mc('다음 단계로 가장 자연스럽게 이어지는 주제는?', ['60갑자와 간지 조합', '영어 발음', '필순 기호학', '색채 심리학'], 0, '지지 확장 다음 단계는 60갑자와 간지 조합입니다.', 'branch.integration', '지지 종합 정리'),
                    $this->tf('지지를 현실 감각과 연결하면 기억 유지에 도움이 된다.', true, '현실 감각과 연결할수록 오래 기억됩니다.', 'branch.integration', '지지 종합 정리'),
                    $this->tf('지지는 계절을 읽을 수 없고 시간 순서만 보여준다.', false, '지지는 시간과 계절, 운동을 함께 보여줍니다.', 'branch.integration', '지지 종합 정리'),
                ],
            ],
        ];

        foreach ($lessons as $index => $lessonData) {
            $lessonId = DB::table('lessons')->insertGetId([
                'learning_track_id' => $trackId,
                'code' => $lessonData['code'],
                'slug' => $lessonData['slug'],
                'title' => $lessonData['title'],
                'objective' => $lessonData['objective'],
                'summary' => $lessonData['summary'],
                'lesson_type' => 'concept',
                'difficulty_level' => 3,
                'estimated_minutes' => $lessonData['estimated_minutes'],
                'unlock_rule_json' => json_encode(['requires' => $lessonData['requires']], JSON_UNESCAPED_UNICODE),
                'sort_order' => $index + 1,
                'publish_status' => 'published',
                'published_at' => $now,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $stepRows = [];
            foreach ($lessonData['steps'] as $stepIndex => $step) {
                $stepRows[] = [
                    'lesson_id' => $lessonId,
                    'step_type' => $step['type'],
                    'title' => $step['title'],
                    'content_markdown' => $step['content'],
                    'payload_json' => json_encode($step['payload'] ?? ['display_mode' => 'text'], JSON_UNESCAPED_UNICODE),
                    'sort_order' => $stepIndex + 1,
                    'is_required' => true,
                    'estimated_minutes' => 3,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('lesson_steps')->insert($stepRows);

            $quizSetId = DB::table('quiz_sets')->insertGetId([
                'lesson_id' => $lessonId,
                'learning_track_id' => $trackId,
                'code' => 'QUIZ_'.strtoupper(str_replace('LESSON_', '', $lessonData['code'])),
                'title' => "{$lessonData['title']} 확인 퀴즈",
                'scope_type' => 'lesson',
                'description' => "{$lessonData['title']}에서 배운 핵심 개념을 바로 점검합니다.",
                'difficulty_level' => 2,
                'pass_score' => 70,
                'publish_status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->insertQuizItems($quizSetId, $lessonData['quiz_items'], $lessonData['code'], $now, 10);
        }

        $trackExamId = DB::table('quiz_sets')->insertGetId([
            'lesson_id' => null,
            'learning_track_id' => $trackId,
            'code' => 'EXAM_EARTH_BRANCH_ADVANCED',
            'title' => '지지 확장 이해 트랙 시험',
            'scope_type' => 'track',
            'description' => '지지의 음양, 계절, 운동, 현실 계절 연결을 종합해서 점검하는 트랙 시험입니다.',
            'difficulty_level' => 3,
            'pass_score' => 80,
            'publish_status' => 'published',
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $trackExamItems = [
            $this->mc('묘(卯)는 음과 양 중 어디에 속할까?', ['양', '음', '중성', '계절에 따라 달라진다'], 1, '묘는 음의 지지입니다.', 'branch.yinyang', '지지의 음양', 'LESSON_BRANCH_ADV_001'),
            $this->mc('다음 중 양의 지지만 모아 놓은 것은?', ['자·인·진·오', '축·묘·사·미', '묘·유·해·축', '사·미·유·해'], 0, '자, 인, 진, 오는 모두 양의 지지입니다.', 'branch.yinyang', '지지의 음양', 'LESSON_BRANCH_ADV_001'),
            $this->mc('해자축은 어느 계절에 해당할까?', ['봄', '여름', '가을', '겨울'], 3, '해자축은 겨울입니다.', 'branch.season', '지지와 계절', 'LESSON_BRANCH_ADV_002'),
            $this->mc('신유술이 나타내는 현실 이미지로 가장 가까운 것은?', ['수확과 정리', '새싹과 출발', '한낮의 열기', '깊은 저장'], 0, '신유술은 가을, 수확과 정리의 이미지입니다.', 'branch.season', '지지와 계절', 'LESSON_BRANCH_ADV_002'),
            $this->mc('인묘진을 한 단어로 묶으면 가장 가까운 것은?', ['겨울의 저장', '봄의 전개', '가을의 수확', '여름의 최고조'], 1, '인묘진은 봄의 전개입니다.', 'branch.season', '지지와 계절', 'LESSON_BRANCH_ADV_002'),
            $this->mc('생장수장에서 겨울과 가장 가까운 운동은?', ['생', '장(長)', '수', '장(藏)'], 3, '겨울은 장(藏), 저장하는 흐름이 강합니다.', 'branch.cycle', '지지의 운동', 'LESSON_BRANCH_ADV_003'),
            $this->mc('여름의 운동감을 가장 잘 설명한 것은?', ['막 트기 시작함', '활짝 드러나 자람', '거두고 접음', '안으로 저장함'], 1, '여름은 장(長), 활짝 드러나는 시기입니다.', 'branch.cycle', '지지의 운동', 'LESSON_BRANCH_ADV_003'),
            $this->mc('차갑고 응축되며 다음 시작을 준비하는 분위기에 가장 가까운 것은?', ['인묘진', '사오미', '신유술', '해자축'], 3, '해자축은 저장과 응축에 가깝습니다.', 'branch.realworld', '현실 계절 연결', 'LESSON_BRANCH_ADV_004'),
            $this->mc('진(辰)을 봄의 끝자락으로 읽는 가장 적절한 이유는?', ['갑자기 겨울로 넘어가기 때문', '봄의 흐름을 정리하며 다음 계절을 준비하기 때문', '여름과 아무 관련이 없기 때문', '오직 동물 상징만 중요하기 때문'], 1, '진은 봄의 마무리이자 다음 계절 준비의 결을 가집니다.', 'branch.realworld', '현실 계절 연결', 'LESSON_BRANCH_ADV_004'),
            $this->mc('지지를 가장 입체적으로 이해한 설명은?', ['12개의 기호를 외우는 것', '음양·계절·운동을 함께 읽는 것', '동물 이름만 기억하는 것', '획수만 아는 것'], 1, '지지는 음양, 계절, 운동을 함께 읽어야 살아납니다.', 'branch.integration', '지지 종합 정리', 'LESSON_BRANCH_ADV_005'),
            $this->tf('유(酉)는 음의 지지다.', true, '유는 음의 지지입니다.', 'branch.yinyang', '지지의 음양', 'LESSON_BRANCH_ADV_001'),
            $this->tf('자(子)는 봄의 지지다.', false, '자는 겨울의 지지입니다.', 'branch.season', '지지와 계절', 'LESSON_BRANCH_ADV_002'),
            $this->tf('사오미는 여름의 흐름을 이룬다.', true, '사오미는 여름입니다.', 'branch.season', '지지와 계절', 'LESSON_BRANCH_ADV_002'),
            $this->tf('장(藏)은 바깥으로 왕성하게 드러나는 운동을 뜻한다.', false, '장(藏)은 안으로 저장하고 품는 운동입니다.', 'branch.cycle', '지지의 운동', 'LESSON_BRANCH_ADV_003'),
            $this->tf('현실 계절과 지지를 연결하면 기억 유지에 도움이 된다.', true, '현실 감각과 연결할수록 오래 기억됩니다.', 'branch.realworld', '현실 계절 연결', 'LESSON_BRANCH_ADV_004'),
            $this->short('인묘진은 무슨 계절인가?', ['봄'], '인묘진은 봄입니다.', 'branch.season', '지지와 계절', 'LESSON_BRANCH_ADV_002'),
            $this->short('지지의 운동을 네 글자로 쓰면?', ['생장수장', '생 장 수 장'], '지지의 운동은 생장수장으로 묶어 읽습니다.', 'branch.cycle', '지지의 운동', 'LESSON_BRANCH_ADV_003'),
            $this->short('자, 인, 진, 오, 신, 술은 음양 중 무엇인가?', ['양'], '자, 인, 진, 오는 양의 흐름에 속합니다.', 'branch.yinyang', '지지의 음양', 'LESSON_BRANCH_ADV_001'),
            $this->selfCheck('왜 진·미·술·축을 계절의 전환점처럼 읽을 수 있는지 한두 문장으로 적어보세요.', '전환점 설명 완료', '계절의 끝자락에서 다음 계절로 넘어가는 정리와 준비의 결이 있기 때문입니다.', 'branch.realworld', '현실 계절 연결', 'LESSON_BRANCH_ADV_004'),
            $this->selfCheck('현실 계절의 장면 하나를 떠올려 지지의 계절과 연결해 설명해보세요.', '현실 계절 연결 작성 완료', '현실 감각과 연결할수록 지지의 계절감이 오래 남고, 해석할 때도 빠르게 떠오릅니다.', 'branch.integration', '지지 종합 정리', 'LESSON_BRANCH_ADV_005'),
        ];

        $this->insertQuizItems($trackExamId, $trackExamItems, 'LESSON_BRANCH_ADV_005', $now, 5);
    }

    private function insertQuizItems(int $quizSetId, array $items, string $lessonCode, $now, int $points): void
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
                    'review_lesson_code' => $item['review_lesson_code'] ?? $lessonCode,
                    'review_prompt' => $item['review_prompt'] ?? $item['prompt_text'],
                    'weak_label' => $item['review_title'],
                ], JSON_UNESCAPED_UNICODE),
                'explanation_text' => $item['explanation_text'],
                'hint_text' => $item['hint_text'] ?? null,
                'sort_order' => $index + 1,
                'points' => $points,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('quiz_items')->insert($rows);
    }

    private function mc(string $prompt, array $choices, int $correctIndex, string $explanation, string $conceptKey, string $reviewTitle, ?string $reviewLessonCode = null): array
    {
        return [
            'question_type' => 'multiple_choice',
            'prompt_text' => $prompt,
            'choices_json' => $choices,
            'answer_payload_json' => ['correct_choice_index' => $correctIndex],
            'explanation_text' => $explanation,
            'concept_key' => $conceptKey,
            'review_title' => $reviewTitle,
            'review_lesson_code' => $reviewLessonCode,
        ];
    }

    private function tf(string $prompt, bool $correctBoolean, string $explanation, string $conceptKey, string $reviewTitle, ?string $reviewLessonCode = null): array
    {
        return [
            'question_type' => 'true_false',
            'prompt_text' => $prompt,
            'answer_payload_json' => ['correct_boolean' => $correctBoolean],
            'explanation_text' => $explanation,
            'concept_key' => $conceptKey,
            'review_title' => $reviewTitle,
            'review_lesson_code' => $reviewLessonCode,
        ];
    }

    private function short(string $prompt, array $acceptedAnswers, string $explanation, string $conceptKey, string $reviewTitle, ?string $reviewLessonCode = null): array
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

    private function selfCheck(string $prompt, string $checkLabel, string $explanation, string $conceptKey, string $reviewTitle, ?string $reviewLessonCode = null): array
    {
        return [
            'question_type' => 'self_check',
            'prompt_text' => $prompt,
            'answer_payload_json' => ['check_label' => $checkLabel],
            'explanation_text' => $explanation,
            'concept_key' => $conceptKey,
            'review_title' => $reviewTitle,
            'review_lesson_code' => $reviewLessonCode,
        ];
    }
}

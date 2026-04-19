<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SajuStructureTrackSeeder extends Seeder
{
    public function run(): void
    {
        // 트랙 추가
        $trackId = DB::table('learning_tracks')->insertGetId([
            'code' => 'TRACK_SAJU_STRUCTURE',
            'slug' => 'saju-structure',
            'title' => '사주의 구조',
            'short_description' => '60갑자, 십신, 대운/세운, 형충회합 등 사주를 읽는 핵심 개념을 배우는 트랙',
            'target_audience' => 'adult_hobby_beginner',
            'difficulty_level' => 3,
            'estimated_total_minutes' => 60,
            'sort_order' => 6,
            'unlock_rule_json' => json_encode([
                'requires' => [
                    ['type' => 'track_exam_passed', 'code' => 'TRACK_EARTH_BRANCH_ADVANCED'],
                ],
            ], JSON_UNESCAPED_UNICODE),
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ============================================================
        // 레슨 1: 60갑자와 간지 조합
        // ============================================================
        $lesson1Id = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_GANJI_001',
            'slug' => 'sixty-ganji',
            'title' => '60갑자와 간지 조합',
            'objective' => '천간 10글자와 지지 12글자가 어떻게 짝을 이뤄 60갑자를 만드는지 이해한다.',
            'summary' => '간지 조합의 원리와 60갑자 순환',
            'lesson_type' => 'concept',
            'difficulty_level' => 3,
            'estimated_minutes' => 15,
            'unlock_rule_json' => json_encode(['requires' => []]),
            'sort_order' => 1,
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $lesson1Id, 'step_type' => 'intro',
                'title' => '간지(干支) — 하늘과 땅의 조합',
                'content_markdown' => "사주의 8글자는 모두 **간지(干支)**로 이루어져 있습니다.\n\n간지란 천간(天干) 1글자와 지지(地支) 1글자를 위아래로 짝지은 것입니다.\n\n예를 들어 **갑자(甲子)**는 천간 '갑(甲)'과 지지 '자(子)'의 조합입니다.\n\n이번 레슨에서는 이 조합이 어떤 규칙으로 만들어지는지 알아봅니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson1Id, 'step_type' => 'explanation',
                'title' => '간지 조합 원리 — 양은 양끼리, 음은 음끼리',
                'content_markdown' => "천간 10글자와 지지 12글자가 짝을 이룰 때, **양(+)끼리, 음(-)끼리만** 결합합니다.\n\n| 양 천간 | 양 지지 | 음 천간 | 음 지지 |\n|---------|---------|---------|----------|\n| 甲 갑 | 子 자, 寅 인, 辰 진, 午 오, 申 신, 戌 술 | 乙 을 | 丑 축, 卯 묘, 巳 사, 未 미, 酉 유, 亥 해 |\n| 丙 병 | | 丁 정 | |\n| 戊 무 | | 己 기 | |\n| 庚 경 | | 辛 신 | |\n| 壬 임 | | 癸 계 | |\n\n그래서 '갑축(甲丑)'이나 '을자(乙子)'같은 조합은 존재하지 않습니다.\n양+양 = 30가지, 음+음 = 30가지, 합계 **60가지**의 조합이 만들어집니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson1Id, 'step_type' => 'explanation',
                'title' => '60갑자 표 — 갑자에서 계해까지',
                'content_markdown' => "60갑자는 **갑자(甲子)**로 시작하여 **계해(癸亥)**로 끝납니다.\n\n```\n 1.甲子  2.乙丑  3.丙寅  4.丁卯  5.戊辰  6.己巳\n 7.庚午  8.辛未  9.壬申 10.癸酉 11.甲戌 12.乙亥\n13.丙子 14.丁丑 15.戊寅 16.己卯 17.庚辰 18.辛巳\n19.壬午 20.癸未 21.甲申 22.乙酉 23.丙戌 24.丁亥\n25.戊子 26.己丑 27.庚寅 28.辛卯 29.壬辰 30.癸巳\n31.甲午 32.乙未 33.丙申 34.丁酉 35.戊戌 36.己亥\n37.庚子 38.辛丑 39.壬寅 40.癸卯 41.甲辰 42.乙巳\n43.丙午 44.丁未 45.戊申 46.己酉 47.庚戌 48.辛亥\n49.壬子 50.癸丑 51.甲寅 52.乙卯 53.丙辰 54.丁巳\n55.戊午 56.己未 57.庚申 58.辛酉 59.壬戌 60.癸亥\n```\n\n60번째인 계해(癸亥)가 끝나면 다시 1번 갑자(甲子)로 돌아갑니다.\n이 순환은 연도, 월, 일, 시간 모두에 적용됩니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson1Id, 'step_type' => 'explanation',
                'title' => '환갑(還甲) — 60년 만의 귀환',
                'content_markdown' => "60갑자가 한 바퀴 도는 데 60년이 걸립니다.\n\n태어난 해의 간지가 60년 뒤에 다시 돌아오는 것을 **환갑(還甲)** 또는 **회갑(回甲)**이라고 합니다.\n\n예를 들어:\n- 1964년 **갑진(甲辰)**년생 → 2024년 다시 **갑진(甲辰)**년 = 환갑\n\n이것이 바로 만 60세에 환갑잔치를 하는 이유입니다. 태어난 해의 기운이 처음으로 다시 돌아왔으니, 말 그대로 '새 인생의 시작'을 의미합니다.\n\n사주에서도 60갑자의 순환은 핵심입니다. 대운, 세운 모두 이 순환을 따라 흐릅니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson1Id, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "**이번 레슨의 핵심**\n\n✅ 간지 = 천간 1글자 + 지지 1글자의 조합\n✅ 양끼리, 음끼리만 결합 → 총 60가지\n✅ 갑자(甲子)로 시작, 계해(癸亥)로 끝나는 순환\n✅ 60년 만에 한 바퀴 = 환갑(還甲)\n✅ 연·월·일·시 모두 60갑자 순환을 따른다",
                'payload_json' => json_encode(['keywords' => ['60갑자', '간지', '환갑', '양양 음음']]),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 2: 십신 — 나와 세상의 관계
        // ============================================================
        $lesson2Id = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_SIPSIN_001',
            'slug' => 'ten-gods-sipsin',
            'title' => '십신(十神) — 나와 세상의 관계',
            'objective' => '일간을 기준으로 십신이 어떻게 결정되는지, 각 십신의 의미를 이해한다.',
            'summary' => '사주 해석의 핵심 도구인 십신 10가지',
            'lesson_type' => 'concept',
            'difficulty_level' => 3,
            'estimated_minutes' => 18,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_GANJI_001']]),
            'sort_order' => 2,
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $lesson2Id, 'step_type' => 'intro',
                'title' => '십신이란? — 나를 기준으로 세상을 읽는 도구',
                'content_markdown' => "사주에서 **일간(日干)**은 '나'를 상징합니다.\n\n**십신(十神)**은 이 '나'를 기준으로 사주의 나머지 7글자가 나와 어떤 관계인지를 나타내는 10가지 이름표입니다.\n\n마치 가족 관계처럼:\n- 나를 도와주는 사람은 누구?\n- 내가 먹여 살리는 사람은 누구?\n- 나를 통제하는 사람은 누구?\n\n이것을 오행의 **생극(生剋)** 관계와 **음양** 일치 여부로 판단합니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson2Id, 'step_type' => 'explanation',
                'title' => '십신 결정 원리',
                'content_markdown' => "십신은 **오행의 5가지 관계** × **음양 일치 여부 2가지** = 10가지로 결정됩니다.\n\n| 관계 | 같은 음양 | 다른 음양 |\n|------|---------|----------|\n| 나와 같은 오행 | **비견** (어깨를 나란히) | **겁재** (빼앗는 자) |\n| 내가 생(生)하는 오행 | **식신** (먹여주는 신) | **상관** (관을 해치는 자) |\n| 내가 극(剋)하는 오행 | **편재** (치우친 재물) | **정재** (바른 재물) |\n| 나를 극(剋)하는 오행 | **편관** (치우친 관직) | **정관** (바른 관직) |\n| 나를 생(生)하는 오행 | **편인** (치우친 인수) | **정인** (바른 인수) |\n\n예를 들어 일간이 甲(갑, 양목)일 때:\n- 甲(양목) = 비견 (같은 오행, 같은 음양)\n- 乙(음목) = 겁재 (같은 오행, 다른 음양)\n- 丙(양화) = 식신 (내가 생하는 화, 같은 음양)\n- 庚(양금) = 편관 (나를 극하는 금, 같은 음양)",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson2Id, 'step_type' => 'explanation',
                'title' => '비겁과 식상 — 자아와 표현',
                'content_markdown' => "**비견(比肩)** — 나와 동등한 존재\n자아, 주체성, 독립심이 강함. 형제, 동료, 라이벌을 상징.\n성격: 자기 주장이 분명하고 독립적. 승부욕과 경쟁심.\n\n**겁재(劫財)** — 나의 것을 빼앗는 자\n비견과 비슷하지만 더 적극적이고 공격적. 사교적이나 재물에 손해 보기 쉬움.\n성격: 대담하고 결단력 있음. 투기적 성향.\n\n---\n\n**식신(食神)** — 먹여주는 신\n내가 생(生)하는 기운. 먹는 것, 표현력, 언어 능력을 상징.\n성격: 낙천적이고 여유로움. 요리, 예술, 말솜씨에 재능.\n\n**상관(傷官)** — 관을 해치는 자\n식신보다 더 날카로운 표현. 기존 질서에 도전하는 반골 기질.\n성격: 창의적이고 자유분방. 비판 정신이 강하고 예술·기술에 뛰어남.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson2Id, 'step_type' => 'explanation',
                'title' => '재성과 관성 — 재물과 직업',
                'content_markdown' => "**편재(偏財)** — 치우친 재물\n내가 극(剋)하는 기운 중 같은 음양. 투자, 사업, 큰 돈의 흐름.\n성격: 통이 크고 인맥이 넓음. 사업가 기질. 남성에게는 애인.\n\n**정재(正財)** — 바른 재물\n내가 극하는 기운 중 다른 음양. 월급, 저축, 꾸준한 수입.\n성격: 성실하고 절약형. 계획적 재테크. 남성에게는 아내.\n\n---\n\n**편관(偏官, 칠살)** — 치우친 관직\n나를 극하는 기운 중 같은 음양. 강압적 통제, 무관(武官).\n성격: 카리스마와 결단력. 군인, 경찰, 외과의사 적성. 여성에게는 애인.\n\n**정관(正官)** — 바른 관직\n나를 극하는 기운 중 다른 음양. 규칙, 법, 명예.\n성격: 품행이 단정하고 책임감 강함. 공무원, 관리직 적성. 여성에게는 남편.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson2Id, 'step_type' => 'explanation',
                'title' => '인성 — 학문과 지혜',
                'content_markdown' => "**편인(偏印)** — 치우친 인수\n나를 생(生)하는 기운 중 같은 음양. 특수한 학문, 종교, 의학.\n성격: 독창적 사고방식. 철학적이고 영감이 풍부. 편식 경향.\n\n**정인(正印)** — 바른 인수\n나를 생하는 기운 중 다른 음양. 정통 학문, 자격증, 어머니.\n성격: 인자하고 배려심 깊음. 학자, 교육자 적성. 인내심이 강함.\n\n---\n\n💡 **십신 기억법**: 5가지 관계를 손가락으로 세어보세요.\n\n👍 같은 오행 = **비겁** (나와 같은 부류)\n☝️ 내가 낳는 = **식상** (내가 표현하는 것)\n✌️ 내가 이기는 = **재성** (내가 거머쥐는 것)\n🤟 나를 이기는 = **관성** (나를 통제하는 것)\n🖐️ 나를 낳는 = **인성** (나를 도와주는 것)",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson2Id, 'step_type' => 'summary',
                'title' => '십신 한눈에 보기',
                'content_markdown' => "| 십신 | 관계 | 키워드 | 상징 |\n|------|------|--------|------|\n| 비견 | 같은 오행·같은 음양 | 자아, 독립 | 형제, 동료 |\n| 겁재 | 같은 오행·다른 음양 | 경쟁, 대담 | 라이벌 |\n| 식신 | 내가 생·같은 음양 | 먹는 것, 표현 | 여유, 예술 |\n| 상관 | 내가 생·다른 음양 | 창의, 반골 | 비판, 기술 |\n| 편재 | 내가 극·같은 음양 | 투자, 사업 | 큰 돈, 애인(남) |\n| 정재 | 내가 극·다른 음양 | 저축, 근면 | 월급, 아내 |\n| 편관 | 나를 극·같은 음양 | 결단, 카리스마 | 무관, 애인(여) |\n| 정관 | 나를 극·다른 음양 | 규칙, 명예 | 관직, 남편 |\n| 편인 | 나를 생·같은 음양 | 독창, 영감 | 특수학문 |\n| 정인 | 나를 생·다른 음양 | 학문, 인자 | 어머니, 자격증 |",
                'payload_json' => json_encode(['keywords' => ['십신', '비겁', '식상', '재성', '관성', '인성']]),
                'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 3: 대운과 세운 — 시간의 흐름
        // ============================================================
        $lesson3Id = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_DAEWOON_001',
            'slug' => 'daewoon-and-sewoon',
            'title' => '대운과 세운 — 시간의 흐름',
            'objective' => '사주 원국과 운(대운·세운)의 관계를 이해하고, 인생의 시간표를 읽는 방법을 배운다.',
            'summary' => '10년 대운과 1년 세운으로 인생의 흐름을 읽기',
            'lesson_type' => 'concept',
            'difficulty_level' => 3,
            'estimated_minutes' => 12,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_SIPSIN_001']]),
            'sort_order' => 3,
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $lesson3Id, 'step_type' => 'intro',
                'title' => '사주 원국 vs 운(運)',
                'content_markdown' => "사주팔자 8글자는 태어날 때 정해진 **변하지 않는 설계도(원국)**입니다.\n\n하지만 같은 설계도라도 **어떤 도로(환경)를 달리느냐**에 따라 삶은 완전히 달라집니다.\n\n이 '도로'가 바로 **운(運)**입니다.\n\n운은 크게 세 가지 층위로 나뉩니다:\n- **대운**: 10년 단위의 큰 흐름\n- **세운**: 1년 단위의 당해 운\n- **월운**: 매달의 기운",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson3Id, 'step_type' => 'explanation',
                'title' => '대운(大運) — 10년 단위의 인생 로드맵',
                'content_markdown' => "**대운**은 월주(태어난 달)를 기준으로 10년마다 바뀌는 큰 운의 흐름입니다.\n\n**결정 방법:**\n1. 성별과 태어난 해의 음양으로 **순행/역행** 결정\n   - 남자 + 양년생 or 여자 + 음년생 → **순행** (월주 다음 간지부터)\n   - 남자 + 음년생 or 여자 + 양년생 → **역행** (월주 이전 간지부터)\n2. 태어난 날부터 다음/이전 절기까지의 일수 ÷ 3 = **대운수(시작 나이)**\n\n**예시:**\n대운수가 8세라면 → 8세부터 첫 번째 대운 시작, 18세에 두 번째 대운, 28세에 세 번째...\n\n대운이 바뀌면 직업, 환경, 가치관에 큰 변화가 올 수 있습니다. \"10년마다 인생이 바뀌는 느낌\"의 정체가 바로 대운입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson3Id, 'step_type' => 'explanation',
                'title' => '세운(歲運)과 월운(月運)',
                'content_markdown' => "**세운(歲運)** — 매년 들어오는 1년짜리 운\n\n해당 연도의 간지가 곧 세운입니다.\n- 2024년 = 갑진(甲辰)년 → 갑진의 기운이 1년간 영향\n- 2025년 = 을사(乙巳)년 → 을사의 기운으로 전환\n\n\"올해 운세\"를 볼 때 가장 먼저 보는 것이 세운입니다.\n\n---\n\n**월운(月運)** — 매달의 기운\n\n각 달도 간지를 가지며, 절기를 기준으로 바뀝니다.\n예: 양력 2월 = 인(寅)월, 3월 = 묘(卯)월...\n\n---\n\n**운의 우선순위:**\n대운(10년) > 세운(1년) > 월운(1달)\n\n대운이 좋은데 세운이 나쁘면 → 큰 틀은 괜찮지만 그 해만 조심\n대운이 나쁜데 세운이 좋으면 → 일시적 호전이지만 근본적 변화는 어려움",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson3Id, 'step_type' => 'summary',
                'title' => '운을 읽는 순서',
                'content_markdown' => "사주를 실전으로 읽을 때의 순서:\n\n**1단계**: 사주 원국 분석 (8글자, 십신, 오행 분포)\n**2단계**: 현재 대운 확인 (지금 어떤 10년 도로 위에 있는지)\n**3단계**: 올해 세운 대입 (그 도로에서 올해 어떤 날씨인지)\n**4단계**: 원국 + 대운 + 세운의 상호작용 분석\n\n✅ 원국 = 나의 체질 (변하지 않음)\n✅ 대운 = 10년짜리 환경 (큰 흐름)\n✅ 세운 = 1년짜리 이벤트 (구체적 사건)\n✅ 세 가지를 겹쳐 봐야 입체적으로 읽힌다",
                'payload_json' => json_encode(['keywords' => ['대운', '세운', '월운', '원국']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 4: 형충회합과 지장간
        // ============================================================
        $lesson4Id = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_RELATION_001',
            'slug' => 'relations-and-hidden-stems',
            'title' => '형충회합과 지장간',
            'objective' => '사주 글자들 간의 상호작용(합·충)과 지지 속 숨은 기운(지장간)을 이해한다.',
            'summary' => '글자들의 화학 반응과 숨은 기운 읽기',
            'lesson_type' => 'concept',
            'difficulty_level' => 3,
            'estimated_minutes' => 15,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_DAEWOON_001']]),
            'sort_order' => 4,
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $lesson4Id, 'step_type' => 'intro',
                'title' => '글자들의 화학 반응',
                'content_markdown' => "사주의 글자들은 가만히 있지 않습니다.\n\n서로 **끌어당기고(합)**, **부딪히고(충)**, **깎고(형)** 하면서 끊임없이 변화합니다.\n\n이것을 통칭 **형충회합(刑沖會合)**이라고 합니다.\n\n마치 화학 원소들이 만나면 반응하듯, 사주의 글자들도 특정 조합이 만나면 변화가 일어납니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson4Id, 'step_type' => 'explanation',
                'title' => '합(合) — 결합과 변화',
                'content_markdown' => "**합**은 글자끼리 서로 끌어당겨 결합하는 현상입니다.\n\n**삼합(三合)** — 지지 3글자가 모여 하나의 오행 국(局)을 이룸\n- 申子辰 → 水局 (신자진 = 물의 세력)\n- 亥卯未 → 木局 (해묘미 = 나무의 세력)\n- 寅午戌 → 火局 (인오술 = 불의 세력)\n- 巳酉丑 → 金局 (사유축 = 쇠의 세력)\n\n**방합(方合)** — 같은 계절 3글자가 모임\n- 寅卯辰 → 봄·木 (인묘진)\n- 巳午未 → 여름·火 (사오미)\n- 申酉戌 → 가을·金 (신유술)\n- 亥子丑 → 겨울·水 (해자축)\n\n**육합(六合)** — 지지 2글자가 1:1로 합\n- 子丑합, 寅亥합, 卯戌합, 辰酉합, 巳申합, 午未합\n\n합이 되면 기존 오행이 **변화**하거나, 두 글자가 **묶여서** 다른 작용을 하기 어려워집니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson4Id, 'step_type' => 'explanation',
                'title' => '충(沖) — 충돌과 자극',
                'content_markdown' => "**충**은 반대 방위에 있는 두 지지가 강하게 부딪히는 현상입니다.\n\n**육충(六沖):**\n| 충 | 의미 |\n|------|------|\n| 子↔午 | 남북 충돌, 급격한 변화 |\n| 丑↔未 | 토끼리 충돌, 고집 대립 |\n| 寅↔申 | 동서 충돌, 활동적 변화 |\n| 卯↔酉 | 동서 충돌, 결단의 시기 |\n| 辰↔戌 | 토끼리 충돌, 창고가 열림 |\n| 巳↔亥 | 수화 충돌, 극적 전환 |\n\n충은 무조건 나쁜 것이 아닙니다.\n- 정체되어 있을 때 → 충이 와야 변화가 시작됨\n- 이사, 이직, 이별 등 **움직임**을 만드는 에너지\n- 대운이나 세운에서 충이 오면 그 해에 큰 변화가 예상됨",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson4Id, 'step_type' => 'explanation',
                'title' => '지장간(地藏干) — 땅 속 숨은 기운',
                'content_markdown' => "**지장간**은 지지(땅) 속에 숨어 있는 천간(하늘)의 에너지입니다.\n\n모든 지지는 내부에 1~3개의 천간을 품고 있습니다.\n\n| 지지 | 지장간 (초기 → 중기 → 정기) |\n|------|---------------------------|\n| 子 자 | 壬 → 癸 |\n| 丑 축 | 癸 → 辛 → 己 |\n| 寅 인 | 戊 → 丙 → 甲 |\n| 卯 묘 | 甲 → 乙 |\n| 辰 진 | 乙 → 癸 → 戊 |\n| 巳 사 | 戊 → 庚 → 丙 |\n| 午 오 | 丙 → 己 → 丁 |\n| 未 미 | 丁 → 乙 → 己 |\n| 申 신 | 己 → 壬 → 庚 |\n| 酉 유 | 庚 → 辛 |\n| 戌 술 | 辛 → 丁 → 戊 |\n| 亥 해 | 戊 → 甲 → 壬 |\n\n지장간을 보면 겉(지지)과 다른 **숨겨진 재능이나 성향**을 발견할 수 있습니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson4Id, 'step_type' => 'explanation',
                'title' => '공망과 신살 — 보조 도구',
                'content_markdown' => "**공망(空亡)** — 비어있는 자리\n\n천간 10개와 지지 12개를 짝지으면 지지 2개가 남습니다. 이 남는 자리를 공망이라 합니다.\n\n공망이 걸린 글자의 기운은 약해지거나 허무감을 느끼기 쉽습니다. 반대로, 그것을 채우려는 강한 갈망으로 더 노력하게 되기도 합니다.\n\n---\n\n**신살(神殺)** — 사주의 특별한 양념\n\n십신이나 오행만으로 설명하기 어려운 독특한 특성을 포착하는 보조 도구입니다.\n\n대표적인 신살:\n- **도화살**: 매력, 인기, 연애 에너지\n- **역마살**: 이동, 변화, 해외 인연\n- **화개살**: 학문, 예술, 종교적 깊이\n- **천을귀인**: 위기 시 도움을 받는 귀인운\n\n신살은 재미있지만, 사주 해석의 중심은 아닙니다. 원국 → 십신 → 대운 → 세운을 먼저 보고, 마지막에 양념처럼 참고하세요.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $lesson4Id, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "**이번 레슨의 핵심**\n\n✅ **합**: 글자끼리 결합 → 새로운 기운으로 변화 (삼합, 방합, 육합)\n✅ **충**: 반대 방위 충돌 → 변화, 이동, 분리 (육충)\n✅ **지장간**: 지지 속 숨은 천간 → 겉과 다른 내면의 진짜 기운\n✅ **공망**: 빈 자리 → 허무감 또는 강한 갈망\n✅ **신살**: 도화, 역마, 화개 등 → 양념 같은 보조 도구\n\n이 개념들을 알면 사주를 훨씬 입체적으로 읽을 수 있습니다.",
                'payload_json' => json_encode(['keywords' => ['합', '충', '지장간', '공망', '신살']]),
                'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

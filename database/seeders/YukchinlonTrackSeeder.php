<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YukchinlonTrackSeeder extends Seeder
{
    public function run(): void
    {
        $trackId = DB::table('learning_tracks')->insertGetId([
            'code' => 'TRACK_YUKCHIN',
            'slug' => 'yukchinron',
            'title' => '육친론과 십성',
            'short_description' => '가족 관계로 사주를 읽는 육친론과 그 바탕이 되는 십성 10가지를 체계적으로 배우는 트랙',
            'target_audience' => 'adult_hobby_beginner',
            'difficulty_level' => 3,
            'estimated_total_minutes' => 60,
            'sort_order' => 7,
            'unlock_rule_json' => json_encode([
                'requires' => [
                    ['type' => 'track_exam_passed', 'code' => 'TRACK_SAJU_STRUCTURE'],
                ],
            ], JSON_UNESCAPED_UNICODE),
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // ============================================================
        // 레슨 1: 육친론이란?
        // ============================================================
        $l1 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_YUKCHIN_001',
            'slug' => 'yukchin-intro',
            'title' => '육친이란? 가족 관계로 읽는 사주',
            'objective' => '육친의 개념, 십성과의 관계, 사주를 가족 관계로 읽는 관점을 이해한다.',
            'summary' => '육친 = 6친 + 십성, 가족 관계 렌즈',
            'lesson_type' => 'concept', 'difficulty_level' => 2, 'estimated_minutes' => 12,
            'unlock_rule_json' => json_encode(['requires' => []]),
            'sort_order' => 1, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l1, 'step_type' => 'intro',
                'title' => '육친(六親)이란?',
                'content_markdown' => "**육친(六親)**은 문자 그대로 **여섯 친족(가족)**을 뜻합니다.\n\n- 부(父): 아버지\n- 모(母): 어머니\n- 형제(兄弟): 형제자매\n- 처(妻): 아내 (또는 남편)\n- 자(子): 자식\n- 본인(己): 나 자신\n\n명리학에서는 사주를 읽을 때 이 **가족 관계의 렌즈**로 해석하는 방식을 **육친론**이라 부릅니다.\n\n사주의 각 글자가 \"나에게 누구에 해당하는가\"를 보면, 복잡한 인간관계와 인생 이벤트를 훨씬 구체적으로 읽을 수 있습니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '육친과 십성(十星)',
                'content_markdown' => "현대 명리학에서는 **육친**을 더 정밀하게 10가지로 세분화한 **십성(十星)**(또는 십신 十神)을 주로 사용합니다.\n\n**왜 6친이 10성이 되는가?**\n\n각 가족 관계를 **음양**으로 다시 나누기 때문입니다.\n\n| 육친 | 십성 (양) | 십성 (음) |\n|------|----------|----------|\n| 나와 같은 존재 (형제) | 비견 | 겁재 |\n| 내가 생하는 것 (자식, 아랫사람) | 식신 | 상관 |\n| 내가 극하는 것 (재물, 아내 — 남자) | 편재 | 정재 |\n| 나를 극하는 것 (관직, 남편 — 여자) | 편관(칠살) | 정관 |\n| 나를 생하는 것 (어머니, 스승) | 편인 | 정인 |\n\n즉 **5가지 관계 × 음양 2 = 10성**입니다.\n\n> 💡 '육친론'이라 부르는 이유는 **가족 관계 중심의 해석**이라는 전통적 관점이 남아 있어서입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '십성 결정 원리 — 일간 기준',
                'content_markdown' => "십성은 **일간(日干)**을 '나'로 놓고, 사주의 나머지 7글자가 나와 어떤 관계인지 판단합니다.\n\n**판단 절차:**\n1. 상대 글자의 **오행**을 본다\n2. 일간과의 **생극 관계**를 확인 (비화·식상·재성·관성·인성)\n3. 일간과 **음양이 같은지 다른지** 확인\n4. 둘을 합쳐 십성 이름을 결정\n\n**예시 — 일간이 甲(양목)일 때:**\n| 상대 글자 | 오행 | 관계 | 음양 | 십성 |\n|---------|------|------|------|------|\n| 甲 | 목 | 비화(같음) | 같은 양 | 비견 |\n| 乙 | 목 | 비화 | 다른 음 | 겁재 |\n| 丙 | 화 | 내가 생함 | 같은 양 | 식신 |\n| 丁 | 화 | 내가 생함 | 다른 음 | 상관 |\n| 戊 | 토 | 내가 극함 | 같은 양 | 편재 |\n| 己 | 토 | 내가 극함 | 다른 음 | 정재 |\n| 庚 | 금 | 나를 극함 | 같은 양 | 편관 |\n| 辛 | 금 | 나를 극함 | 다른 음 | 정관 |\n| 壬 | 수 | 나를 생함 | 같은 양 | 편인 |\n| 癸 | 수 | 나를 생함 | 다른 음 | 정인 |",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '육친의 성별 차이 — 남자 vs 여자',
                'content_markdown' => "같은 십성이라도 **사주 주인의 성별**에 따라 가리키는 가족이 달라집니다.\n\n**남자 사주에서:**\n| 십성 | 상징 가족 |\n|------|----------|\n| 비견·겁재 | 형제, 동료 |\n| 식신·상관 | 조카, 장모, 후배 |\n| 편재 | 아버지, 애인 |\n| 정재 | 아내 |\n| 편관·정관 | 자식 |\n| 편인·정인 | 어머니 |\n\n**여자 사주에서:**\n| 십성 | 상징 가족 |\n|------|----------|\n| 비견·겁재 | 형제, 동료 |\n| 식신·상관 | 자식 |\n| 편재·정재 | 아버지, 시어머니 |\n| 편관 | 애인 |\n| 정관 | 남편 |\n| 편인·정인 | 어머니 |\n\n**핵심 차이점:**\n- 남자: **재성 = 아내**, **관성 = 자식**\n- 여자: **관성 = 남편**, **식상 = 자식**\n\n왜? 전통 관점에서 남자는 \"돈(재성)\"으로 아내를 맞이하고 \"관직(관성)\"이 자식이었고, 여자는 \"관직(관성)\"이 남편이고 \"내가 낳는 것(식상)\"이 자식이기 때문입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "✅ 육친 = 가족 관계 6가지(부·모·형제·처·자·본인)\n✅ 현대 실전: 십성(10성) 체계로 세분화해 사용\n✅ 기준: 일간(나) — 상대 글자와의 오행 관계 + 음양 여부\n✅ 성별에 따라 같은 십성도 가리키는 가족이 다름\n✅ 남자 재성=아내, 여자 관성=남편 (핵심 포인트)",
                'payload_json' => json_encode(['keywords' => ['육친론', '십성', '일간 기준', '성별 차이']]),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 2: 비견·겁재 (자아와 형제)
        // ============================================================
        $l2 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_YUKCHIN_002',
            'slug' => 'yukchin-bigyeop',
            'title' => '비견·겁재 — 나와 형제, 경쟁자',
            'objective' => '비견과 겁재의 차이와 공통점, 현대적 의미를 이해한다.',
            'summary' => '자아·독립·경쟁의 기운',
            'lesson_type' => 'concept', 'difficulty_level' => 2, 'estimated_minutes' => 10,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_YUKCHIN_001']]),
            'sort_order' => 2, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l2, 'step_type' => 'intro',
                'title' => '비겁(比劫) — 나와 같은 편',
                'content_markdown' => "**비견(比肩)**과 **겁재(劫財)**는 함께 **비겁(比劫)**이라고 부릅니다.\n\n- 일간과 **같은 오행**\n- 비견 = 음양도 **같음** (나와 똑같은 쌍둥이 같은 존재)\n- 겁재 = 음양이 **다름** (나와 같은 오행이지만 성격은 반대)\n\n육친으로는 **형제, 동료, 친구, 경쟁자** — 나와 같은 입장에 있는 사람들을 상징합니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '비견(比肩) — 어깨를 나란히',
                'content_markdown' => "**비견**은 \"어깨(肩)를 나란히(比) 하는 존재\"라는 뜻.\n\n**심리적 의미:**\n- 강한 자아와 주체성\n- 독립심, 자기 주장\n- 친구·동료와 대등한 관계\n- 협력보다는 평행선\n\n**직업 성향:**\n- 프리랜서, 자영업, 1인 창업자\n- 자기 이름으로 일하는 직업 (변호사·회계사·디자이너)\n- 스포츠 선수, 공예가\n\n**장점:** 남에게 휘둘리지 않음, 끈기, 자기 확신\n**단점:** 고집, 협업 약함, 자기 방식 고수로 기회 놓침\n\n**비견이 많으면:** 재성(재물·아내)이 흔들리기 쉬움 — 돈 관리·결혼 유의\n**비견이 약하면:** 자존감 저하, 줏대 없음 — 주체성 기르는 훈련 필요",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '겁재(劫財) — 빼앗는 동료',
                'content_markdown' => "**겁재**는 \"내 재물을 빼앗는(劫) 자(財)\" 라는 뜻.\n\n**심리적 의미:**\n- 비견보다 더 **적극적·공격적**\n- 사교성은 뛰어나지만 대가가 따름\n- 승부욕·모험심·도전 정신\n- 의리와 배짱, 단 이면에 이해관계도 존재\n\n**직업 성향:**\n- 영업·마케팅·홍보 등 대면 설득형\n- 투자·투기·도박성 비즈니스 (리스크 큰 분야)\n- 연예·방송 등 경쟁이 치열한 분야\n\n**장점:** 추진력, 인맥, 배짱, 어려운 판에서 돌파\n**단점:** 재물 손실·사기·배신 위험, 구설수\n\n**겁재가 많으면:** 재물 관리 어려움, 경쟁 과열, 동업 주의\n**겁재가 적절히 있으면:** 강한 친구·조력자 획득, 경쟁을 통한 성장",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'summary',
                'title' => '비견·겁재 한눈 비교',
                'content_markdown' => "| 구분 | 비견 | 겁재 |\n|------|------|------|\n| 음양 | 일간과 **같음** | 일간과 **다름** |\n| 성격 | 조용한 자아·독립 | 적극적·승부욕 |\n| 관계 | 동등·평행 | 경쟁·긴장 |\n| 직업 | 프리랜서·전문직 | 영업·투자·방송 |\n| 장점 | 주체성·끈기 | 추진력·인맥 |\n| 단점 | 고집·협업 약함 | 손실·구설 |\n\n✅ 비겁은 **동료·경쟁자**의 기운 — 인간관계의 배경음악\n✅ 비견은 \"조용한 동료\", 겁재는 \"시끄러운 동료\"\n✅ 많으면 재물에 타격, 적절하면 든든한 지원군",
                'payload_json' => json_encode(['keywords' => ['비견', '겁재', '비겁', '자아']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 3: 식신·상관 (표현과 자식)
        // ============================================================
        $l3 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_YUKCHIN_003',
            'slug' => 'yukchin-siksang',
            'title' => '식신·상관 — 표현과 자식, 재능',
            'objective' => '식신과 상관의 차이, 표현력과 자식 운의 관점에서 의미를 이해한다.',
            'summary' => '내가 낳는 기운 — 여유·재능·비판',
            'lesson_type' => 'concept', 'difficulty_level' => 2, 'estimated_minutes' => 11,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_YUKCHIN_002']]),
            'sort_order' => 3, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l3, 'step_type' => 'intro',
                'title' => '식상(食傷) — 내가 낳는 기운',
                'content_markdown' => "**식신(食神)**과 **상관(傷官)**을 합쳐 **식상(食傷)**이라고 부릅니다.\n\n- 일간이 **생하는(낳는) 오행**\n- 식신 = 음양이 **같음** (순한 자식)\n- 상관 = 음양이 **다름** (까칠한 자식)\n\n육친으로는 **여자에게는 자식**, **남자에게는 장모·조카**. 현대 실전에서는 **표현력·재능·창작·말솜씨**의 지표로 더 많이 쓰입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '식신(食神) — 먹여주는 순한 기운',
                'content_markdown' => "**식신**은 \"먹여주는(食) 신(神)\" — 여유와 풍요를 상징.\n\n**심리적 의미:**\n- 낙천적·여유로움\n- 언어 감각, 말솜씨\n- 먹는 것·즐기는 것을 좋아함\n- 예술·취미·미식 감각\n\n**직업 성향:**\n- 요리사, 방송인, 교육자, 번역가\n- 서비스업, 호텔, 외식업\n- 디자이너, 일러스트레이터\n\n**장점:** 사람을 편안하게 함, 표현력, 재능 다방면\n**단점:** 안일함, 결정 미루기, 집중력 부족\n\n**식신이 적절하면:** 재능으로 먹고살기 편함, 대인관계 원만\n**식신이 너무 많으면:** 게으름·과식·비만·직업 안정감 떨어짐",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '상관(傷官) — 관(官)을 상(傷)하는 반골',
                'content_markdown' => "**상관**은 \"관(官)을 상(傷)하게 하는 자\" — 기존 질서에 도전하는 기운.\n\n**심리적 의미:**\n- 창의성·독창성·개성\n- 비판·반골·기존 권위에 저항\n- 말이 날카롭고 논리적\n- 예술적·기술적 천재성\n\n**직업 성향:**\n- 작가, 시나리오 작가, 카피라이터\n- 기술 전문직 (엔지니어, 프로그래머)\n- 변호사, 평론가, 개그맨\n- 예술가 (특히 전위·실험적)\n\n**장점:** 천재적 창의성, 통찰력, 기성을 뛰어넘는 발상\n**단점:** 말실수, 관재구설, 기존 조직과 충돌\n\n**상관이 적절하면:** 창작·비평에서 두각, 자기 분야 독보적\n**상관이 너무 강하면:** 구설·관재·이직 잦음 — 말과 글 조심\n\n> 💡 **여자의 상관은 특히 남편운 주의** — 상관은 관(남편)을 상(傷)하므로",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'summary',
                'title' => '식신·상관 한눈 비교',
                'content_markdown' => "| 구분 | 식신 | 상관 |\n|------|------|------|\n| 음양 | 일간과 **같음** | 일간과 **다름** |\n| 성격 | 온화·여유·낙천 | 날카로움·반골·창의 |\n| 표현 | 부드러운 언어·미식 | 예리한 비판·창작 |\n| 직업 | 요리·교육·서비스 | 작가·기술·평론 |\n| 장점 | 재능·원만 | 통찰·독창 |\n| 단점 | 안일·결정미루기 | 구설·관재 |\n\n✅ 식상은 **표현·재능의 기운** — 현대에 매우 중요한 자산\n✅ 식신은 \"부드러운 표현\", 상관은 \"날카로운 표현\"\n✅ 여자 사주에선 자식·남편 운과 직결되므로 더 신중히 해석",
                'payload_json' => json_encode(['keywords' => ['식신', '상관', '식상', '표현력', '자식']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 4: 재성과 관성 (재물, 직업, 배우자)
        // ============================================================
        $l4 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_YUKCHIN_004',
            'slug' => 'yukchin-jaekwan',
            'title' => '재성·관성 — 재물·직업·배우자',
            'objective' => '편재/정재/편관/정관의 의미와 남녀 배우자운 해석 차이를 이해한다.',
            'summary' => '재성 = 재물·아내(남자), 관성 = 직업·남편(여자)',
            'lesson_type' => 'concept', 'difficulty_level' => 3, 'estimated_minutes' => 14,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_YUKCHIN_003']]),
            'sort_order' => 4, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l4, 'step_type' => 'intro',
                'title' => '재관(財官) — 세속적 성공의 두 축',
                'content_markdown' => "**재성(財星)**과 **관성(官星)**은 전통적으로 사주에서 **세속적 성공**을 읽는 두 축입니다.\n\n- **재성**: 내가 **극하는** 오행 — 내가 손에 쥐는 것 (재물·아내)\n- **관성**: 나를 **극하는** 오행 — 나를 규율하는 것 (직업·남편)\n\n재성은 편재(偏財)·정재(正財), 관성은 편관(偏官)·정관(正官)으로 나뉩니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '편재(偏財) — 큰 돈, 사업가의 돈',
                'content_markdown' => "**편재**는 \"치우친(偏) 재물(財)\" — 큰 흐름의 돈.\n\n**심리·직업:**\n- 통이 크고 사교적, 인맥이 넓음\n- 사업가·투자가·무역가 기질\n- 남자에게는 **애인**의 상징 (정재 = 아내, 편재 = 애인)\n\n**장점:** 돈을 크게 벌 수 있음, 네트워크가 자산\n**단점:** 돈 관리가 들쭉날쭉, 투기 위험, 여자관계 복잡 (남자 사주)\n\n**편재가 좋으면:** 자영업·사업·투자로 큰 성공\n**편재가 나쁘면:** 큰 실패·빚·연애 복잡",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '정재(正財) — 월급, 성실한 돈',
                'content_markdown' => "**정재**는 \"바른(正) 재물(財)\" — 꾸준하고 성실한 돈.\n\n**심리·직업:**\n- 성실·근면·절약\n- 정해진 월급, 안정적 수입\n- 남자에게는 **아내**의 상징\n\n**장점:** 계획적·안정적 재테크, 신뢰받음\n**단점:** 도전·모험 부족, 융통성 약함\n\n**정재가 좋으면:** 공무원·회사원·전문직으로 안정 + 좋은 아내\n**정재가 손상되면:** 수입 불안정, 결혼 문제 (남자 사주)\n\n> 💡 **편재 vs 정재**: 편재 = 큰 돈이지만 들락날락, 정재 = 적어도 꾸준. 현대에선 두 기운이 모두 있으면 이상적",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '편관(偏官, 七殺) — 강압적 통제',
                'content_markdown' => "**편관**은 \"치우친(偏) 관(官)\" 또는 **칠살(七殺)** — 강한 통제와 시련.\n\n**심리·직업:**\n- 카리스마·결단력·추진력\n- 무관(武官)·군인·경찰·검사\n- 응급의학·외과의사·소방관\n- 여자에게는 **애인**의 상징 (정관 = 남편, 편관 = 애인)\n\n**장점:** 역경 속에서 강함, 리더십, 위기 돌파\n**단점:** 충돌·사건사고·스트레스\n\n**편관이 좋으면:** 큰 조직의 리더, 권력형 직업에서 두각\n**편관이 나쁘면:** 구설·시비·관재·건강 타격",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '정관(正官) — 명예, 공직, 남편',
                'content_markdown' => "**정관**은 \"바른(正) 관(官)\" — 규율·명예·안정.\n\n**심리·직업:**\n- 책임감·준법정신·품격\n- 공무원·교사·판사·공기업\n- 여자에게는 **남편**의 상징\n\n**장점:** 신뢰받음, 안정적 커리어, 명예로움\n**단점:** 융통성 부족, 답답한 규율주의\n\n**정관이 좋으면:** 공직·전문직으로 명예 + 좋은 남편 (여자)\n**정관이 손상되면:** 직업 변동 잦음, 결혼 문제 (여자 사주)\n\n> 💡 **편관 vs 정관**: 편관 = 날카로운 권력, 정관 = 품격 있는 명예. 편관은 '싸워서 얻는 것', 정관은 '주어진 자리'",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'summary',
                'title' => '재성·관성 한눈 비교',
                'content_markdown' => "**재성 (내가 극하는 것)**\n| 구분 | 편재 | 정재 |\n|------|------|------|\n| 돈 | 큰 흐름·사업 | 월급·저축 |\n| 남자 | 애인 | 아내 |\n| 성향 | 통큰·사교적 | 근면·성실 |\n| 위험 | 투기·복잡한 관계 | 답답·소극적 |\n\n**관성 (나를 극하는 것)**\n| 구분 | 편관(칠살) | 정관 |\n|------|-----------|------|\n| 직업 | 무관·전문직 | 공직·교육 |\n| 여자 | 애인 | 남편 |\n| 성향 | 카리스마·돌파 | 책임·품격 |\n| 위험 | 충돌·사건사고 | 융통성 부족 |\n\n✅ 재성과 관성이 **균형 있게** 있으면 세속적 성공\n✅ 너무 강하면 부담, 너무 약하면 기회 부족\n✅ 남녀 배우자운이 다르게 적용됨 — 성별 맥락 필수",
                'payload_json' => json_encode(['keywords' => ['편재', '정재', '편관', '칠살', '정관']]),
                'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 5: 인성 (학문, 어머니, 자격증)
        // ============================================================
        $l5 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_YUKCHIN_005',
            'slug' => 'yukchin-insung',
            'title' => '인성(印星) — 학문·어머니·자격',
            'objective' => '편인과 정인의 차이와 학문·자기계발·어머니운의 해석 포인트를 이해한다.',
            'summary' => '나를 생하는 기운 — 공부·자격증·지원',
            'lesson_type' => 'concept', 'difficulty_level' => 3, 'estimated_minutes' => 11,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_YUKCHIN_004']]),
            'sort_order' => 5, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l5, 'step_type' => 'intro',
                'title' => '인성(印星) — 나를 돕는 기운',
                'content_markdown' => "**편인(偏印)**과 **정인(正印)**을 합쳐 **인성(印星)** 이라 합니다.\n\n- 일간을 **생하는(낳아주는) 오행**\n- 편인 = 음양이 **같음** (특수한 배움)\n- 정인 = 음양이 **다름** (정통 학문)\n\n육친으로는 **어머니, 스승, 지원자**. 현대 실전에서는 **학문·자격증·자기계발**의 지표입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l5, 'step_type' => 'explanation',
                'title' => '편인(偏印) — 독창적 지혜',
                'content_markdown' => "**편인**은 \"치우친(偏) 인(印)\" — 비주류·특수한 배움.\n\n**심리·직업:**\n- 독창적 사고, 영감, 직관\n- 철학·종교·심리·역술·의학(한의·대체)\n- 편식 경향 (좋아하는 분야만 깊게 파고듦)\n- 예술적 감각, 신비로운 매력\n\n**장점:** 남들이 못 보는 것을 봄, 통찰력, 창의성\n**단점:** 우유부단, 실천력 약함, 망상·외로움\n\n**편인이 좋으면:** 전문 분야에서 독보적, 정신세계 깊이\n**편인이 너무 많으면:** 현실감 부족, 계획 세우다 끝남",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l5, 'step_type' => 'explanation',
                'title' => '정인(正印) — 정통 학문과 어머니',
                'content_markdown' => "**정인**은 \"바른(正) 인(印)\" — 정통 학문·자격증·어머니.\n\n**심리·직업:**\n- 인자함·배려심·인내심\n- 학자·교수·교사·연구원\n- 자격증이 필요한 전문직 (의사·변호사·회계사)\n- 인문·사회·정통 예술\n\n**장점:** 신뢰받음, 꾸준한 공부, 품위\n**단점:** 행동력 부족, 보수적, 의존성\n\n**정인이 좋으면:** 학문적 성공, 자격증·명예, 어머니 덕\n**정인이 손상되면:** 공부 중단, 자격 시험 낙방, 모친 문제",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l5, 'step_type' => 'summary',
                'title' => '인성 정리 + 십성 전체 복습',
                'content_markdown' => "**편인 vs 정인**\n| 구분 | 편인 | 정인 |\n|------|------|------|\n| 학문 | 비주류·특수 | 정통·주류 |\n| 직업 | 역술·철학·한의 | 교사·교수·전문직 |\n| 성향 | 독창·영감·편식 | 인자·성실·보수 |\n| 장점 | 통찰·창의 | 신뢰·꾸준 |\n\n---\n\n**🎯 십성 10가지 한눈에**\n\n| 기운 | 양음 같음 | 양음 다름 |\n|------|----------|----------|\n| **비겁** (같은 오행) | 비견 | 겁재 |\n| **식상** (내가 생함) | 식신 | 상관 |\n| **재성** (내가 극함) | 편재 | 정재 |\n| **관성** (나를 극함) | 편관 | 정관 |\n| **인성** (나를 생함) | 편인 | 정인 |\n\n✅ 다섯 관계 × 음양 2 = 십성 10\n✅ 편(偏) = 음양이 같아 편향적, 정(正) = 음양이 달라 균형\n✅ 육친론의 핵심은 **일간 기준 + 생극 관계 + 음양 여부**\n✅ 다음 단계: **시험 메뉴 → 육친론(십성)** 과목으로 실전 문제 풀기!",
                'payload_json' => json_encode(['keywords' => ['편인', '정인', '인성', '십성 요약']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

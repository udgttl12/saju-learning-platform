<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MyeongrihakBasicsTrackSeeder extends Seeder
{
    public function run(): void
    {
        $trackId = DB::table('learning_tracks')->insertGetId([
            'code' => 'TRACK_MYEONGRIHAK_BASICS',
            'slug' => 'myeongrihak-basics',
            'title' => '명리학 기초 입문',
            'short_description' => '명리학이란 무엇인지, 역사와 유파, 음양론, 용신, 현대 활용까지 — 사주 공부의 첫 번째 나침반',
            'target_audience' => 'adult_hobby_beginner',
            'difficulty_level' => 1,
            'estimated_total_minutes' => 50,
            'sort_order' => 0,
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // ============================================================
        // 레슨 1: 명리학이란?
        // ============================================================
        $l1 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_MRH_INTRO',
            'slug' => 'what-is-myeongrihak',
            'title' => '명리학이란 무엇인가',
            'objective' => '명리학의 정의와 목적, 그리고 미신이 아닌 학문으로서의 위치를 이해한다.',
            'summary' => '명리학의 정의, 목적, 그리고 흔한 오해 바로잡기',
            'lesson_type' => 'concept', 'difficulty_level' => 1, 'estimated_minutes' => 10,
            'unlock_rule_json' => json_encode(['requires' => []]),
            'sort_order' => 1, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l1, 'step_type' => 'intro',
                'title' => '명리학 — 운명을 읽는 학문',
                'content_markdown' => "**명리학(命理學)**이란?\n\n사람이 태어난 연·월·일·시를 간지(干支)로 변환한 8개의 글자(사주팔자)를 바탕으로, 음양오행의 상생·상극 원리를 분석하여 인생의 흐름을 이해하는 학문입니다.\n\n단순히 미래를 점치는 것이 아니라, **우주와 자연의 질서 속에서 '나'라는 개인의 기질과 적성, 인생의 때(타이밍)를 이해**하고자 하는 고도의 인문학적 철학입니다.\n\n명리학의 궁극적 목적은 **안심입명(安心立命)** — 삶을 수용하고 마음을 편안하게 하는 것에 있습니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '명리학에 대한 흔한 오해 바로잡기',
                'content_markdown' => "명리학을 처음 접하면 흔히 갖게 되는 오해들이 있습니다.\n\n**❌ \"사주는 미신이다\"**\n→ 사주팔자와 60갑자는 태양과 지구의 운행을 바탕으로 한 절기력(달력 체계)이자, 태어난 시점의 '우주 시간표'입니다.\n\n**❌ \"완벽하게 좋거나 무조건 나쁜 사주가 있다\"**\n→ 좋고 나쁜 것이 아니라 서로 '다를 뿐'입니다. 균형 잡힌 사주가 평탄하지만, 쏠린 사주도 그것이 곧 강점이 됩니다.\n\n**❌ \"제왕절개로 좋은 사주를 고를 수 있다\"**\n→ 신생아가 첫 호흡을 하는 순간이 본인의 진짜 사주입니다. 부모의 그릇(복) 안에서 태어납니다.\n\n**❌ \"쌍둥이는 운명이 같다\"**\n→ 빠져나오는 순서와 성장 환경이 다르기 때문에 같은 시간에 태어나도 삶은 달라집니다.\n\n**❌ \"대운이 오면 무조건 대박\"**\n→ 대운은 '큰 행운'이 아니라 '10년 단위의 변화 주기'입니다. 오르막인지 내리막인지가 중요합니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '명리학의 현대 활용',
                'content_markdown' => "현대 사회에서 명리학은 다양한 영역에서 활용되고 있습니다.\n\n**🧠 자기이해 (나 사용 설명서)**\nMBTI, 에니어그램처럼 개인의 기질과 성격을 파악하는 도구로 활용. 자신의 장단점을 객관적으로 파악하고 본인에게 맞는 삶의 방식을 찾는 데 도움.\n\n**💼 진로 및 적성 탐색**\n십신과 용신 분석으로 잠재력, 직업 적성을 구체적으로 파악. 전공 선택, 이직, 사업 시기 판단에 활용.\n\n**💕 궁합과 대인관계**\n궁합은 운명을 바꾸는 것이 아니라, 서로 부족한 부분을 보충해주기 위해 봅니다. 부부, 동업자, 직장 내 관계 조율에 활용.\n\n**🧘 심리 상담 (안심입명)**\n맹목적인 운명론이 아니라, 불안을 해소하고 삶을 수용하게 도와주는 상담 도구로서의 역할.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "✅ 명리학 = 사주팔자로 인생의 흐름을 이해하는 학문\n✅ 미신이 아닌 절기력(달력) 기반의 인문학적 철학\n✅ 목적: 안심입명 — 삶을 수용하고 마음을 편안하게\n✅ 현대 활용: 자기이해, 진로탐색, 궁합, 심리상담",
                'payload_json' => json_encode(['keywords' => ['명리학', '안심입명', '사주팔자']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 2: 명리학의 역사와 유파
        // ============================================================
        $l2 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_MRH_HISTORY',
            'slug' => 'myeongrihak-history-schools',
            'title' => '명리학의 역사와 유파',
            'objective' => '고법에서 신법(자평명리학)으로의 발전 과정과 주요 유파·고전을 이해한다.',
            'summary' => '당사주에서 자평명리학까지, 그리고 현대의 다양한 유파',
            'lesson_type' => 'concept', 'difficulty_level' => 1, 'estimated_minutes' => 12,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_MRH_INTRO']]),
            'sort_order' => 2, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l2, 'step_type' => 'intro',
                'title' => '고법에서 신법으로 — 명리학의 진화',
                'content_markdown' => "명리학의 역사는 크게 두 시대로 나뉩니다.\n\n**고법명리 (당사주)** — 당나라까지\n이허중이라는 학자가 집대성. **태어난 '해(年)'를 기준**으로 운명을 봄.\n가문과 핏줄을 중시하던 시대상이 반영. 띠를 기준으로 보는 '당사주'가 이 흔적.\n\n**신법명리 (자평명리학)** — 송나라 이후\n서자평이라는 인물이 혁명적 전환. 기준을 해(年)에서 **태어난 '날(日)'의 천간(일간)**으로 변경.\n그의 이름을 따서 **자평학(子平學)**이라 불림.\n\n이 전환이 왜 중요할까요? '어떤 집안에서 태어났는가'가 아니라 **'나 자신이 어떤 사람인가'**에 초점을 맞추게 된 것입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '3대 명리 고전 — 이론의 바탕',
                'content_markdown' => "자평명리학의 교과서로 불리는 핵심 고전 3권입니다.\n\n📚 **연해자평(淵海子平)** — 자평학의 시작\n서자평의 이론을 집대성한 최초의 체계적 교재. '일간(나)'을 기준으로 사주를 보는 방법과 십신(十神) 등 기초 간명법을 정립.\n\n📚 **적천수(滴天髓)** — 억부론의 교과서\n사주 전체 에너지의 강약을 분석. 넘치면 억누르고(抑) 부족하면 도와주어(扶) 균형(중화)을 맞추는 **억부론(抑扶論)**의 핵심.\n\n📚 **자평진전(子平眞詮)** — 격국론의 최고봉\n태어난 달(월령)을 가장 중요하게 봄. 사회적 무기, 직업적 적성을 결정짓는 사주의 틀(격국)을 논리적으로 분석하는 **격국론(格局論)**.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '계절을 중시하는 고전과 현대 유파',
                'content_markdown' => "**🌦 궁통보감(窮通寶鑑) — 조후론**\n사주를 자연의 계절에 비유. 너무 춥거나 덥지 않게 온도와 습도(한난조습)를 맞춰주는 글자를 찾는 **조후론(調候論)**에 특화.\n\n---\n\n현대에 각광받는 실전 유파:\n\n🔥 **맹파명리(盲派命理)**\n중국 맹인 점술가들의 구전 비법을 체계화. 격국이나 강약보다 글자들이 어떻게 '일(做功)'을 만들어내는지에 집중. 재물운, 구체적 사건 예측에 탁월.\n\n🎨 **물상론(物象論)**\n사주의 한자들을 자연의 사물(큰 나무, 촛불, 호수 등)로 치환하여 한 폭의 풍경화처럼 직관적으로 해석. 빠르고 감각적인 판단에 강점.\n\n💡 현대 사주 = 자평명리학(뼈대) + 억부론(강약) + 격국론(사회적 무기) + 조후론(계절 환경) + 실전 기법(맹파/물상)",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'summary',
                'title' => '유파 한눈에 보기',
                'content_markdown' => "| 고전/유파 | 핵심 이론 | 초점 |\n|----------|---------|------|\n| 연해자평 | 자평학 기초 | 일간 중심, 십신 |\n| 적천수 | 억부론 | 강약 균형(중화) |\n| 자평진전 | 격국론 | 사회적 무기, 직업 |\n| 궁통보감 | 조후론 | 계절(온도·습도) |\n| 맹파명리 | 주공(做功) | 구체적 사건 예측 |\n| 물상론 | 물상(物象) | 직관적 풍경 해석 |\n\n✅ 입문자 추천 순서: 음양오행 → 천간지지 → 십신 → 격국/억부 → 실전",
                'payload_json' => json_encode(['keywords' => ['자평명리학', '억부론', '격국론', '조후론', '맹파명리']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 3: 음양론 — 모든 것의 시작
        // ============================================================
        $l3 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_MRH_YINYANG',
            'slug' => 'yin-yang-theory',
            'title' => '음양론(陰陽論) — 모든 것의 시작',
            'objective' => '음양의 원리와 사주에서의 적용 방식을 이해한다.',
            'summary' => '음양의 개념, 천간합/천간충, 사주에서의 음양 읽기',
            'lesson_type' => 'concept', 'difficulty_level' => 1, 'estimated_minutes' => 12,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_MRH_HISTORY']]),
            'sort_order' => 3, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l3, 'step_type' => 'intro',
                'title' => '음양 — 세상을 나누는 두 가지 기운',
                'content_markdown' => "**음양(陰陽)**은 우주에 존재하는 상반되면서도 상호 보완적인 두 가지 기운입니다.\n\n| 양(陽) | 음(陰) |\n|--------|--------|\n| 태양 ☀️ | 달 🌙 |\n| 밝음 | 어둠 |\n| 움직임(動) | 고요함(靜) |\n| 상승, 확장 | 하강, 수축 |\n| 외면, 겉 | 내면, 속 |\n| 남성적 | 여성적 |\n| 홀수(1,3,5...) | 짝수(2,4,6...) |\n\n음양은 대립이 아니라 **순환**입니다. 낮이 있으면 밤이 오고, 여름이 지나면 겨울이 옵니다.\n\n명리학의 모든 개념은 이 음양의 원리에서 출발합니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '사주에서의 음양 적용',
                'content_markdown' => "사주에서는 천간 10글자와 지지 12글자를 각각 양과 음으로 나눕니다.\n\n**천간의 음양:**\n| 양(+) | 음(-) |\n|-------|-------|\n| 甲 갑 | 乙 을 |\n| 丙 병 | 丁 정 |\n| 戊 무 | 己 기 |\n| 庚 경 | 辛 신 |\n| 壬 임 | 癸 계 |\n\n**지지의 음양:**\n| 양(+) | 음(-) |\n|-------|-------|\n| 子 자, 寅 인, 辰 진 | 丑 축, 卯 묘, 巳 사 |\n| 午 오, 申 신, 戌 술 | 未 미, 酉 유, 亥 해 |\n\n일간(나)이 양인지 음인지에 따라:\n- **양일간**: 외향적, 능동적, 적극적 성향\n- **음일간**: 내향적, 수동적, 섬세한 성향\n\n같은 오행이라도 양과 음은 성격이 크게 다릅니다. (예: 갑목=큰 나무 vs 을목=풀꽃)",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '천간합(天干合)과 천간충(天干沖)',
                'content_markdown' => "천간 글자들끼리도 음양에 따라 합(合)과 충(沖)이 일어납니다.\n\n**천간합 (5가지)** — 음양이 다른 극(剋) 관계가 결합\n| 합 | 합화 오행 |\n|------|----------|\n| 甲 + 己 | 土로 변화 |\n| 乙 + 庚 | 金으로 변화 |\n| 丙 + 辛 | 水로 변화 |\n| 丁 + 壬 | 木으로 변화 |\n| 戊 + 癸 | 火로 변화 |\n\n합이 되면 원래의 오행이 새로운 오행으로 바뀔 수 있습니다.\n\n---\n\n**천간충 (4가지)** — 같은 음양의 극(剋) 관계가 충돌\n| 충 | 관계 |\n|------|------|\n| 甲 ↔ 庚 | 양목 vs 양금 |\n| 乙 ↔ 辛 | 음목 vs 음금 |\n| 丙 ↔ 壬 | 양화 vs 양수 |\n| 丁 ↔ 癸 | 음화 vs 음수 |\n\n충은 강한 부딪힘으로, 변화와 갈등의 에너지를 만듭니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "✅ 음양 = 대립이 아닌 순환, 만물의 기본 원리\n✅ 양일간 = 외향적·적극적, 음일간 = 내향적·섬세\n✅ 같은 오행도 양/음에 따라 성격이 다름 (큰 나무 vs 풀꽃)\n✅ 천간합 = 음양 다른 글자끼리 결합 → 새 오행으로 변화\n✅ 천간충 = 같은 음양끼리 충돌 → 변화와 갈등",
                'payload_json' => json_encode(['keywords' => ['음양', '천간합', '천간충', '양일간', '음일간']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 4: 용신 — 내 사주의 핵심 무기
        // ============================================================
        $l4 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_MRH_YONGSHIN',
            'slug' => 'yongshin-core-weapon',
            'title' => '용신(用神) — 내 사주의 핵심 무기',
            'objective' => '용신의 개념과 사주 분석에서의 중요성을 이해하고, 명리학 공부의 로드맵을 파악한다.',
            'summary' => '용신의 의미, 종류, 그리고 명리학 공부 순서',
            'lesson_type' => 'concept', 'difficulty_level' => 2, 'estimated_minutes' => 12,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_MRH_YINYANG']]),
            'sort_order' => 4, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l4, 'step_type' => 'intro',
                'title' => '용신이란? — 내 인생의 핵심 도구',
                'content_markdown' => "**용신(用神)**은 글자 그대로 '사주에서 내가 가져다 써야 하는 가장 유용한 에너지'입니다.\n\n내 인생을 성공적으로 살아가기 위한 **핵심 도구**이자 **무기**에 비유할 수 있습니다.\n\n예를 들어:\n- 용신이 **재성(재물)**인 사람 → 이익을 추구하는 사업·투자에 적성\n- 용신이 **정관(규칙)**인 사람 → 공명정대한 공무원·관리자에 적성\n- 용신이 **식신(표현)**인 사람 → 요리, 글쓰기, 예술에 적성\n\n사주를 풀 때 나에게 맞는 용신을 정확히 찾는 것이 **사주 분석의 가장 중요한 핵심**입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '용신을 찾는 대표적인 방법',
                'content_markdown' => "용신을 찾는 방법은 유파에 따라 다르지만, 대표적인 세 가지 접근법이 있습니다.\n\n**1. 억부용신 (적천수 계열)**\n사주의 일간(나)이 너무 강하면 억누르고, 너무 약하면 도와주는 글자를 용신으로 삼음.\n→ 균형과 중화를 추구\n\n**2. 격국용신 (자평진전 계열)**\n월령(태어난 달)을 기준으로 사주의 격(格)을 세우고, 그 격을 성립시키거나 보호하는 글자를 용신으로 삼음.\n→ 사회적 무기와 성공 패턴을 추구\n\n**3. 조후용신 (궁통보감 계열)**\n태어난 계절의 기후를 고려하여, 사주가 너무 춥거나 덥지 않게 균형을 맞춰주는 글자를 용신으로 삼음.\n→ 자연적 환경의 조화를 추구\n\n💡 실제 사주 상담에서는 이 세 가지를 **종합적으로** 고려합니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '명리학 공부 로드맵',
                'content_markdown' => "명리학을 체계적으로 공부하는 추천 순서입니다.\n\n**1단계: 기초 원리** 🌱\n음양오행의 특성과 상생·상극 원리 익히기\n\n**2단계: 글자 익히기** ✏️\n천간 10글자, 지지 12글자의 성격과 속성 암기\n\n**3단계: 구조 이해** 🏗️\n사주팔자 구성 원리, 만세력 보는 법, 근묘화실\n\n**4단계: 관계 읽기** 🔗\n십신(十神)과 육친(六親) — 나와 다른 글자들의 관계\n\n**5단계: 숨은 기운** 🔍\n지장간과 형충회합(합·충) 변화\n\n**6단계: 실전 분석** ⚔️\n용신 찾기, 대운/세운 해석, 통변\n\n**7단계: 심화 상담** 🎯\n직장운, 사업운, 연애·결혼운 등 실전 사례 연습\n\n이 플랫폼의 학습 트랙이 바로 이 순서를 따르고 있습니다!",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "✅ 용신 = 내 사주에서 가장 유용한 핵심 에너지\n✅ 억부용신(강약 균형) + 격국용신(사회적 무기) + 조후용신(계절 조화)\n✅ 용신에 따라 가치관, 직업 적성, 행동 방식이 결정됨\n✅ 공부 순서: 음양오행 → 천간지지 → 사주 구조 → 십신 → 합충 → 용신 → 실전\n\n🎓 이 트랙을 마치면 사주의 기본 체계를 한눈에 조망할 수 있는 시야가 생깁니다. 다음 트랙들에서 각 개념을 하나씩 깊이 있게 배워보세요!",
                'payload_json' => json_encode(['keywords' => ['용신', '억부용신', '격국용신', '조후용신', '로드맵']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TwelveShinsalTrackSeeder extends Seeder
{
    public function run(): void
    {
        $trackId = DB::table('learning_tracks')->insertGetId([
            'code' => 'TRACK_TWELVE_SHINSAL',
            'slug' => 'twelve-shinsal',
            'title' => '12신살 완전 정복',
            'short_description' => '겁살부터 화개살까지 12신살의 의미와 결정 원리, 실전 해석법을 배우는 트랙',
            'target_audience' => 'adult_hobby_beginner',
            'difficulty_level' => 3,
            'estimated_total_minutes' => 55,
            'sort_order' => 7,
            'publish_status' => 'published',
            'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // ============================================================
        // 레슨 1: 12신살이란?
        // ============================================================
        $l1 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_SHINSAL_001',
            'slug' => 'twelve-shinsal-intro',
            'title' => '12신살이란? 뼈대 이해하기',
            'objective' => '12신살의 정의, 결정 원리, 해석 순서를 이해한다.',
            'summary' => '신살의 의미 + 삼합 기준 결정법',
            'lesson_type' => 'concept', 'difficulty_level' => 2, 'estimated_minutes' => 12,
            'unlock_rule_json' => json_encode(['requires' => []]),
            'sort_order' => 1, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l1, 'step_type' => 'intro',
                'title' => '신살(神殺)이란 무엇인가',
                'content_markdown' => "**신살(神殺)**은 사주의 글자에 특별한 **별명** 또는 **양념**처럼 붙는 해석 도구입니다.\n\n- **神(신)**: 길(吉)한 신 — 도와주는 작용\n- **殺(살)**: 흉(凶)한 살 — 방해하거나 자극하는 작용\n\n신살은 수십 가지가 있지만, 그중에서도 **12신살(十二神殺)** 은 가장 체계적이고 기본이 되는 묶음입니다.\n\n12신살은 **지지 12글자**에 대응하여 12가지 이름을 붙이며, 일지(日支)나 연지(年支)를 기준으로 다른 지지가 어떤 신살에 해당하는지 살펴봅니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '12신살 이름과 순서',
                'content_markdown' => "12신살은 고정된 순서를 따릅니다.\n\n| 순서 | 이름 | 한자 | 키워드 |\n|------|------|------|--------|\n| 1 | 겁살 | 劫殺 | 강제적 손실, 도난 |\n| 2 | 재살 | 災殺 | 재난, 관재구설 |\n| 3 | 천살 | 天殺 | 하늘의 시련, 돌발 사건 |\n| 4 | 지살 | 地殺 | 이동, 시작 |\n| 5 | 년살 | 年殺(도화) | 인기, 매력, 연애 |\n| 6 | 월살 | 月殺 | 고립, 소진 |\n| 7 | 망신살 | 亡身殺 | 체면 손상, 노출 |\n| 8 | 장성살 | 將星殺 | 리더십, 권위 |\n| 9 | 반안살 | 攀鞍殺 | 승진, 명예 |\n| 10 | 역마살 | 驛馬殺 | 이동, 여행, 해외 |\n| 11 | 육해살 | 六害殺 | 질병, 방해 |\n| 12 | 화개살 | 華蓋殺 | 학문, 종교, 고독 |\n\n기억하는 방법: **겁·재·천·지·년·월·망·장·반·역·육·화** 순으로 외웁니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '12신살 결정 원리 — 삼합을 기준으로',
                'content_markdown' => "12신살은 **삼합(三合)** 을 중심으로 결정됩니다.\n\n**기준지**는 일지(日支) 또는 연지(年支)가 속한 삼합을 먼저 찾습니다.\n\n| 기준 삼합 | 포함 지지 | 생지 → 왕지 → 고지 |\n|----------|----------|------------------|\n| 수국(水局) | 申子辰 | 申 → 子 → 辰 |\n| 목국(木局) | 亥卯未 | 亥 → 卯 → 未 |\n| 화국(火局) | 寅午戌 | 寅 → 午 → 戌 |\n| 금국(金局) | 巳酉丑 | 巳 → 酉 → 丑 |\n\n**핵심 규칙:**\n- **장성살** = 왕지(子, 卯, 午, 酉) — 삼합의 중심\n- **지살** = 생지(寅, 申, 巳, 亥) — 삼합의 시작\n- **화개살** = 고지(辰, 戌, 丑, 未) — 삼합의 마무리\n- 나머지는 장성살 기준으로 순서대로 배치\n\n예: 일지가 **子**(수국) → 장성살은 子. 그 다음 축이 반안, 인이 역마...",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 4,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'explanation',
                'title' => '12신살 전체 대응표',
                'content_markdown' => "기준지(일지/연지)별로 각 지지가 어떤 신살에 해당하는지 정리한 표입니다.\n\n| 기준지 | 겁살 | 재살 | 천살 | 지살 | 년살 | 월살 | 망신 | 장성 | 반안 | 역마 | 육해 | 화개 |\n|-------|------|------|------|------|------|------|------|------|------|------|------|------|\n| 申子辰(수) | 巳 | 午 | 未 | 申 | 酉 | 戌 | 亥 | 子 | 丑 | 寅 | 卯 | 辰 |\n| 亥卯未(목) | 申 | 酉 | 戌 | 亥 | 子 | 丑 | 寅 | 卯 | 辰 | 巳 | 午 | 未 |\n| 寅午戌(화) | 亥 | 子 | 丑 | 寅 | 卯 | 辰 | 巳 | 午 | 未 | 申 | 酉 | 戌 |\n| 巳酉丑(금) | 寅 | 卯 | 辰 | 巳 | 午 | 未 | 申 | 酉 | 戌 | 亥 | 子 | 丑 |\n\n**읽는 법:**\n1. 본인의 일지(또는 연지)가 어느 삼합에 속하는지 확인\n2. 해당 삼합 행을 찾기\n3. 나머지 지지들이 어느 신살에 해당하는지 표에서 읽기\n\n예: 일지 **午** → 화국(寅午戌) → 연지가 **申** 이면 역마살.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l1, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "✅ 12신살 = 지지 12글자에 붙는 길흉 이름표\n✅ 순서: **겁·재·천·지·년·월·망·장·반·역·육·화**\n✅ 기준: 일지(또는 연지)의 삼합을 찾아서 대응\n✅ 핵심 앵커: 왕지=장성살, 생지=지살, 고지=화개살\n✅ 신살은 양념 — 원국·십신·대운을 먼저 본 뒤 참고",
                'payload_json' => json_encode(['keywords' => ['12신살', '삼합', '장성살', '지살', '화개살']]),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 2: 4대 핵심 신살 — 역마·도화(년살)·화개·장성
        // ============================================================
        $l2 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_SHINSAL_002',
            'slug' => 'twelve-shinsal-big-four',
            'title' => '4대 핵심 신살 — 역마·도화·화개·장성',
            'objective' => '실전에서 가장 자주 언급되는 4대 신살의 의미와 작용을 이해한다.',
            'summary' => '역마(이동), 도화(매력), 화개(예술·고독), 장성(리더십)',
            'lesson_type' => 'concept', 'difficulty_level' => 2, 'estimated_minutes' => 15,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_SHINSAL_001']]),
            'sort_order' => 2, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l2, 'step_type' => 'intro',
                'title' => '왜 이 4개를 먼저 배우는가',
                'content_markdown' => "12신살 중에서도 **역마·도화·화개·장성** 이 네 가지는 실전 상담에서 가장 많이 언급됩니다.\n\n이들은 **삼합의 생지·왕지·고지**에 정확히 대응하기 때문에, 사주 전반의 성격을 요약하는 강력한 지표가 됩니다.\n\n이번 레슨에서 이 4개의 성격을 확실히 잡으면, 나머지 8개는 한결 쉽게 느껴집니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '역마살(驛馬殺) — 움직임과 이동',
                'content_markdown' => "**역마살 위치**: 삼합의 생지(生支) — 寅, 申, 巳, 亥\n\n**역마**는 글자 그대로 **역참의 말**. 끊임없이 움직이는 에너지입니다.\n\n**긍정적 작용:**\n- 활동성, 적극성, 개척 정신\n- 해외·타지 인연, 무역·여행 관련 직업\n- 승진·이사·이직의 타이밍\n\n**주의할 점:**\n- 한곳에 머무르기 어려워 불안정해 보일 수 있음\n- 충(沖)이 겹치면 교통사고·분주한 일 주의\n\n**현대적 해석:**\n글로벌 시대에 역마살은 오히려 **자산**입니다. 해외 업무, 외국어, 여행·관광·물류·IT 리모트 워크 등 이동성이 자산이 되는 직업에 적합.\n\n역마살이 대운이나 세운에 들어오면 이사, 이직, 해외 기회 등 **환경 변화**의 해가 됩니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '도화살(년살) — 매력과 인기',
                'content_markdown' => "**도화살 위치**: 삼합의 왕지(旺支) — 子, 午, 卯, 酉\n(12신살 순서상 공식 명칭은 '년살'이지만 '도화살'이라고도 자주 부릅니다.)\n\n**도화**는 복숭아꽃. 사람의 눈길을 사로잡는 기운입니다.\n\n**긍정적 작용:**\n- 매력·미모·인기·카리스마\n- 예술·연예·방송·마케팅·서비스업에 유리\n- 대인 친화력과 분위기 메이커 기질\n\n**주의할 점:**\n- 연애가 복잡해지거나 구설수\n- 너무 많으면 자기관리가 흐트러질 가능성\n\n**현대적 해석:**\n도화살은 더 이상 '바람기'로만 치부되지 않습니다. 인플루언서, 배우, 가수, 유튜버, 세일즈 등 **매력이 자산**인 시대에 강력한 무기.\n\n도화살이 있고 직업이 맞으면 이름이 알려지고 인기를 얻는 경향이 있습니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '화개살(華蓋殺) — 학문·예술·고독',
                'content_markdown' => "**화개살 위치**: 삼합의 고지(庫支) — 辰, 戌, 丑, 未\n\n**화개**는 '화려함을 덮다'. 속으로 깊이 파고드는 기운입니다.\n\n**긍정적 작용:**\n- 학문·예술·종교·철학에 깊이\n- 남들과 다른 독창적 사고\n- 전문 연구·창작·수행에 강함\n\n**주의할 점:**\n- 혼자 있는 시간을 좋아해 사회성이 약해 보일 수 있음\n- 이상주의가 강해 현실과 괴리\n\n**현대적 해석:**\n화개살은 **디지털 시대의 크리에이터·연구자·작가·개발자·예술가** 기질. 혼자 깊이 파고드는 시간이 필요한 직업에 강력한 자산.\n\n화개살이 많으면 \"왜 이렇게 외톨이 같지?\" 싶을 수 있지만, 그 고독의 시간이 작품·연구의 씨앗이 됩니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'explanation',
                'title' => '장성살(將星殺) — 리더십과 권위',
                'content_markdown' => "**장성살 위치**: 삼합의 왕지 — 사실상 도화살과 같은 자리(子, 午, 卯, 酉).\n단, 12신살 체계에서 **장성살**은 본인 삼합의 중심을 가리키므로, 본인의 그릇·주체성을 상징합니다.\n\n**긍정적 작용:**\n- 리더십, 결단력, 책임감\n- 조직의 중심·중추 역할\n- 관리자·지휘관·CEO 기질\n\n**주의할 점:**\n- 자존심이 강해 부딪히기 쉬움\n- 지지 않으려는 고집\n\n**현대적 해석:**\n장성살은 곧 **나의 주무대**. 본인의 중심 자리이므로, 여기서 버티는 힘이 강합니다. 경영·공직·군·경찰·의료·교육 등 책임 있는 자리에 적합.\n\n> 💡 **도화살과 장성살의 차이**: 같은 왕지 자리라도, \"남의 시선\"을 끌면 도화, \"자기 주도\"로 발휘되면 장성.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l2, 'step_type' => 'summary',
                'title' => '핵심 정리',
                'content_markdown' => "| 신살 | 위치 | 키워드 | 현대 직업 |\n|------|------|--------|----------|\n| 역마살 | 寅·申·巳·亥 | 이동·활동·해외 | 무역·관광·물류·글로벌 |\n| 도화(년)살 | 子·午·卯·酉 | 매력·인기·예술 | 연예·인플루언서·세일즈 |\n| 화개살 | 辰·戌·丑·未 | 학문·고독·창작 | 연구·예술·개발·종교 |\n| 장성살 | 子·午·卯·酉 | 리더십·권위 | 경영·공직·관리자 |\n\n✅ 이 4개를 먼저 잡으면 12신살의 80%는 이해한 것\n✅ 도화/장성은 같은 자리 — 해석은 작용 방향에 따라 다름\n✅ 신살은 '저주'가 아니라 '성향' — 현대 직업·환경에 맞추면 강점",
                'payload_json' => json_encode(['keywords' => ['역마살', '도화살', '화개살', '장성살']]),
                'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 3: 나머지 8대 신살
        // ============================================================
        $l3 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_SHINSAL_003',
            'slug' => 'twelve-shinsal-other-eight',
            'title' => '나머지 8대 신살 — 겁·재·천·월·망·반·육·년',
            'objective' => '4대 핵심 외 8개 신살의 의미와 현대적 해석을 이해한다.',
            'summary' => '각 신살의 고유한 색을 파악',
            'lesson_type' => 'concept', 'difficulty_level' => 3, 'estimated_minutes' => 18,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_SHINSAL_002']]),
            'sort_order' => 3, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l3, 'step_type' => 'intro',
                'title' => '각 신살은 고유한 색을 가진다',
                'content_markdown' => "4대 핵심 신살은 전체 성격을 잡아주지만, 나머지 8개 신살은 **구체적인 상황과 사건**을 가리키는 경우가 많습니다.\n\n이번 레슨에서는 각 신살을 **키워드 + 현대 해석 + 주의점** 형식으로 한 눈에 정리합니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '겁살(劫殺) · 재살(災殺)',
                'content_markdown' => "**겁살(劫殺)** — 빼앗길 劫\n\n- **키워드**: 강제적 손실, 도난, 배신, 격변\n- **긍정적 활용**: 위기관리 능력, 큰 그림을 보는 눈, 결단\n- **현대 해석**: 보험·감사·수사·응급·사업 리스크 관리 등 위기 대응형 직업\n- **주의**: 대운/세운에서 겁살이 올 때 재물 손실·사기 조심\n\n---\n\n**재살(災殺)** — 재난 災\n\n- **키워드**: 관재구설, 시비, 사건사고, 감금\n- **긍정적 활용**: 법률·송무·의료·군경 등 위험을 다루는 직업\n- **현대 해석**: 송사(변호사), 외과의사, 경찰, 보안업\n- **주의**: 다툼·분쟁·감옥·입원 조심. 특히 운에서 들어올 때 계약/법적 문제 각별히 살필 것",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '천살(天殺) · 지살(地殺)',
                'content_markdown' => "**천살(天殺)** — 하늘의 시련\n\n- **키워드**: 하늘이 내린 시련, 예상치 못한 일, 불가항력\n- **긍정적 활용**: 종교·철학·상담 등 정신적 성찰의 계기\n- **현대 해석**: 목사·상담사·기수(氣修)·명상 지도자 등\n- **주의**: 겸손한 태도 유지, 교만 주의\n\n---\n\n**지살(地殺)** — 땅의 이동\n\n- **키워드**: 이동, 새 출발, 이사, 전학\n- **긍정적 활용**: 역마살의 완화판 — 생활권 내 이동, 부동산·운수·배달\n- **현대 해석**: 물류, 운수, 부동산 중개, 지역 이동 잦은 영업\n- **주의**: 너무 안주하지 않고 적절히 움직여야 기운이 돈다",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '월살(月殺) · 망신살(亡身殺)',
                'content_markdown' => "**월살(月殺)** — 달의 고립\n\n- **키워드**: 소진, 고립, 건강 저하, 기운 약함\n- **긍정적 활용**: 명상·요양·의료·심리 상담 등 '회복'을 다루는 분야\n- **현대 해석**: 간호, 요양보호, 웰니스, 명상 지도\n- **주의**: 과로하지 말 것. 체력·정신력 관리 우선\n\n---\n\n**망신살(亡身殺)** — 몸이 드러나는 살\n\n- **키워드**: 체면 손상, 노출, 구설, 숨기고 싶은 것이 드러남\n- **긍정적 활용**: 오히려 **자기 노출이 자산**이 되는 직업 — 방송인, 크리에이터, 정치인, 퍼블릭 피규어\n- **현대 해석**: SNS 인플루언서, 방송·엔터, 공개 강연자\n- **주의**: 품행 관리. 공개되기 쉬운 포지션이므로 작은 실수도 크게 번짐",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'explanation',
                'title' => '반안살(攀鞍殺) · 육해살(六害殺)',
                'content_markdown' => "**반안살(攀鞍殺)** — 안장을 오른다\n\n- **키워드**: 승진, 명예, 타이틀, 자리\n- **긍정적 활용**: 공직·대기업·관리자·교수 등 **조직 내 상승**에 유리\n- **현대 해석**: 관리자, 공무원, 임원, 학계 지위\n- **주의**: 명예욕 과도하면 실리 놓침. 균형 필요\n\n---\n\n**육해살(六害殺)** — 여섯 가지 방해\n\n- **키워드**: 건강 문제, 방해, 인간관계 트러블, 지병\n- **긍정적 활용**: 의료·보건·복지·돌봄 분야\n- **현대 해석**: 의사, 약사, 재활치료사, 사회복지사\n- **주의**: 본인 건강 관리 1순위. 지구력이 약할 수 있으므로 꾸준한 루틴 필요",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l3, 'step_type' => 'summary',
                'title' => '나머지 8대 신살 한눈 보기',
                'content_markdown' => "| 신살 | 키워드 | 현대 직업 |\n|------|--------|----------|\n| 겁살 | 손실·격변·결단 | 위기관리·감사·보험 |\n| 재살 | 시비·관재·위험 | 법률·수사·외과 |\n| 천살 | 시련·불가항력 | 종교·상담·명상 |\n| 지살 | 이동·이사·새출발 | 물류·부동산·영업 |\n| 월살 | 고립·소진·회복 | 의료·요양·웰니스 |\n| 망신살 | 노출·체면·공개 | 방송·인플루언서·정치 |\n| 반안살 | 승진·명예·자리 | 공직·관리자·학계 |\n| 육해살 | 방해·건강·돌봄 | 의사·복지·재활 |\n\n✅ 신살은 '운명의 저주'가 아니라 **성향과 환경의 지표**\n✅ 같은 신살도 사주의 다른 글자·대운과 결합되면 의미가 크게 달라진다\n✅ 본인의 삶에 맞춰 긍정적 활용 방법을 찾는 것이 핵심",
                'payload_json' => json_encode(['keywords' => ['겁살', '재살', '천살', '지살', '월살', '망신살', '반안살', '육해살']]),
                'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 레슨 4: 실전 활용
        // ============================================================
        $l4 = DB::table('lessons')->insertGetId([
            'learning_track_id' => $trackId,
            'code' => 'LESSON_SHINSAL_004',
            'slug' => 'twelve-shinsal-practice',
            'title' => '실전 활용 — 사주에서 신살 찾고 읽기',
            'objective' => '실제 사주에서 12신살을 찾아 해석하는 절차를 익힌다.',
            'summary' => '일지 기준 → 삼합 확인 → 신살 매핑 → 다른 요소와 종합',
            'lesson_type' => 'concept', 'difficulty_level' => 3, 'estimated_minutes' => 10,
            'unlock_rule_json' => json_encode(['requires' => ['LESSON_SHINSAL_003']]),
            'sort_order' => 4, 'publish_status' => 'published', 'published_at' => now(),
            'created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('lesson_steps')->insert([
            [
                'lesson_id' => $l4, 'step_type' => 'intro',
                'title' => '신살은 맨 나중에 본다',
                'content_markdown' => "신살 해석은 **사주를 읽는 순서의 마지막**에 옵니다.\n\n1. 원국 (8글자, 오행 분포, 음양 균형)\n2. 십신 (나와 관계)\n3. 대운·세운 (시간의 흐름)\n4. 합·충 (글자 간 화학 반응)\n5. **신살** — 양념 · 구체적 사건의 힌트\n\n신살을 먼저 보고 결론짓는 것은 위험합니다. 다른 요소로 해석한 후, 신살로 **디테일을 보강**하는 것이 정석입니다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 1, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '신살 찾기 3단계',
                'content_markdown' => "**1단계: 기준지 정하기**\n- 전통: 연지 기준 (출생년 지지)\n- 현대: 일지 기준이 더 개인적·실용적\n- 두 기준 모두 보고 겹치는 것을 중심으로\n\n**2단계: 기준지의 삼합 확인**\n| 기준지 | 속한 삼합 |\n|-------|---------|\n| 申·子·辰 | 수국 |\n| 亥·卯·未 | 목국 |\n| 寅·午·戌 | 화국 |\n| 巳·酉·丑 | 금국 |\n\n**3단계: 사주 나머지 글자 매핑**\n사주 원국의 네 지지(연·월·일·시)와 대운·세운의 지지를 놓고, 레슨 1의 대응표에서 각각이 어느 신살인지 확인.\n\n예시: 일지가 午(화국) · 시지가 寅 → 寅은 화국 기준 **지살** → 이동·새 출발의 기운.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 2, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'explanation',
                'title' => '해석 시 주의점',
                'content_markdown' => "**1. 신살은 단독으로 좋다/나쁘다 판단 금물**\n같은 역마살도 사주 전체 균형에 따라 축복이 되거나 부담이 된다.\n\n**2. 합·충이 있으면 신살의 작용이 크게 달라진다**\n- 신살이 다른 지지와 삼합을 이루면 작용이 크게 증폭\n- 신살이 충을 맞으면 작용이 흔들리거나 반대로 더 극적으로 발현\n\n**3. 대운·세운의 신살**\n- 원국에 없던 신살이 운에서 들어오면 그 해·그 10년의 **이벤트 힌트**\n- 원국 신살을 건드리는 운은 해당 신살이 \"활성화\" 되는 시기\n\n**4. 신살 개수로 판단 금지**\n많다고 해서 무조건 나쁘거나 좋은 것이 아니다. 전체 그림을 본 뒤, 신살은 마지막 디테일로만 쓴다.",
                'payload_json' => json_encode(['display_mode' => 'text']),
                'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lesson_id' => $l4, 'step_type' => 'summary',
                'title' => '12신살 마스터를 위한 체크리스트',
                'content_markdown' => "✅ 12신살 이름과 순서 암기 (겁·재·천·지·년·월·망·장·반·역·육·화)\n✅ 4대 핵심 신살의 의미 (역마·도화·화개·장성)\n✅ 삼합 기준 신살 찾기 3단계\n✅ 신살은 양념 — 원국 → 십신 → 대운 → 합충 → 신살 순서\n✅ 단독 판단 금지, 전체 맥락과 함께 보기\n\n> 💡 다음 단계 추천: **시험 메뉴 → 12신살** 과목으로 실전 문제를 풀어보세요!",
                'payload_json' => json_encode(['keywords' => ['12신살 실전', '해석 절차', '신살 체크리스트']]),
                'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

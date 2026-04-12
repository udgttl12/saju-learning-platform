<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonContentEnrichSeeder extends Seeder
{
    public function run(): void
    {
        // 기존 lesson_steps의 max id 확인
        $maxId = DB::table('lesson_steps')->max('id') ?? 14;
        $id = $maxId + 1;

        $newSteps = [];

        // ============================================================
        // 레슨 1: 한자 구조와 획 맛보기 (기존 3스텝 → 추가 2스텝)
        // ============================================================
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 1, 'step_type' => 'explanation',
            'title' => '한자의 6가지 기본 획',
            'content_markdown' => "한자를 구성하는 획은 크게 6가지 기본 유형이 있습니다.\n\n**① 가로획(횡, 一)**: 왼쪽에서 오른쪽으로\n**② 세로획(수, 丨)**: 위에서 아래로\n**③ 삐침(별, 丿)**: 오른쪽 위에서 왼쪽 아래로\n**④ 파임(나, 乀)**: 왼쪽 위에서 오른쪽 아래로\n**⑤ 꺾음(절, 乛)**: 방향이 꺾이는 획\n**⑥ 점(점, 丶)**: 짧고 힘차게 찍는 획\n\n이 6가지 획이 어떻게 조합되느냐에 따라 수천 개의 한자가 만들어집니다. 사주에 나오는 27자는 대부분 획이 적은 간단한 글자이므로 부담 없이 시작할 수 있습니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 1, 'step_type' => 'explanation',
            'title' => '필순의 기본 규칙',
            'content_markdown' => "한자를 쓸 때 획의 순서(필순)에는 기본 규칙이 있습니다.\n\n**1. 위에서 아래로** (三 → 一 二 三)\n**2. 왼쪽에서 오른쪽으로** (川 → ｜ ｜ ｜)\n**3. 가로가 먼저, 세로가 나중** (十 → 一 ｜)\n**4. 삐침이 먼저, 파임이 나중** (人 → 丿 乀)\n**5. 바깥에서 안으로** (月 → 바깥 틀 먼저)\n**6. 가운데 먼저, 양쪽 나중** (小 → ｜ 丿 丶)\n\n완벽하게 외우지 않아도 됩니다. '큰 흐름'만 느끼면서 따라 쓰다 보면 자연스럽게 손에 익습니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];

        // ============================================================
        // 레슨 3: 오행 5글자 전체보기 (기존 3스텝 → 추가 5스텝)
        // ============================================================
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 3, 'step_type' => 'explanation',
            'title' => '오행의 상생 관계 — 서로를 낳고 도와주는 순환',
            'content_markdown' => "오행은 서로 돕고 살리는 **상생(相生)** 관계를 맺고 있습니다.\n\n🌳→🔥 **목생화**: 나무가 타서 불을 키워줍니다\n🔥→🟤 **화생토**: 불이 타고 남은 재가 흙이 됩니다\n🟤→🪨 **토생금**: 흙 속에서 오랜 시간에 걸쳐 광물이 생깁니다\n🪨→💧 **금생수**: 차가운 금속 표면에 물방울이 맺힙니다\n💧→🌳 **수생목**: 물이 나무를 자라게 합니다\n\n이 순환은 자연의 계절과도 대응됩니다: 봄(목) → 여름(화) → 환절기(토) → 가을(금) → 겨울(수) → 다시 봄으로.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 3, 'step_type' => 'explanation',
            'title' => '오행의 상극 관계 — 서로를 이기고 제어하는 균형',
            'content_markdown' => "오행은 서로 통제하는 **상극(相剋)** 관계도 있습니다.\n\n🌳⚔️🟤 **목극토**: 나무가 뿌리로 흙을 뚫고 움켜쥡니다\n🟤⚔️💧 **토극수**: 흙으로 제방을 쌓아 물을 막습니다\n💧⚔️🔥 **수극화**: 물을 끼얹어 불을 꺼뜨립니다\n🔥⚔️🪨 **화극금**: 뜨거운 불이 쇠를 녹입니다\n🪨⚔️🌳 **금극목**: 쇠 도끼로 나무를 베어냅니다\n\n상생과 상극은 '좋고 나쁨'이 아니라 **균형과 견제**의 원리입니다. 사주에서 오행이 적절히 상생·상극하면 조화로운 삶이 됩니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 3, 'step_type' => 'explanation',
            'title' => '오행의 계절·방위·색상 매핑',
            'content_markdown' => "각 오행에는 계절, 방위, 색상이 대응됩니다. 사주를 읽을 때 이 연결을 알면 직관적으로 이해가 됩니다.\n\n| 오행 | 계절 | 방위 | 색상 | 성격 키워드 |\n|------|------|------|------|------------|\n| 木 목 | 봄 | 동쪽 | 초록 | 성장, 시작, 창의 |\n| 火 화 | 여름 | 남쪽 | 빨강 | 열정, 표현, 확산 |\n| 土 토 | 환절기 | 중앙 | 노랑 | 안정, 중재, 인내 |\n| 金 금 | 가을 | 서쪽 | 흰색 | 결단, 정리, 절제 |\n| 水 수 | 겨울 | 북쪽 | 검정 | 지혜, 유연, 저장 |",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 2,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 3, 'step_type' => 'explanation',
            'title' => '오행 기억법 — 씨앗에서 열매까지',
            'content_markdown' => "오행을 '1년의 순환'으로 상상하면 쉽게 기억됩니다.\n\n🌱 **봄(木)**: 얼어붙은 겨울 땅을 뚫고 새싹이 솟아오릅니다. 뻗어나가는 생명력.\n☀️ **여름(火)**: 뜨거운 태양 아래서 화려하게 꽃을 피우며 열기를 뿜어냅니다.\n🌾 **환절기(土)**: 성장을 멈추고 에너지를 갈무리하여 열매 맺을 준비를 합니다.\n🍂 **가을(金)**: 쓸데없는 잎사귀는 단호하게 베어내고 단단한 알맹이만 수확합니다.\n❄️ **겨울(水)**: 씨앗을 깊은 땅속에 품고 다음 봄을 지혜롭게 기다립니다.\n\n이 이야기를 떠올리면서 木→火→土→金→水 순서를 자연스럽게 기억해보세요.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 7, 'is_required' => false, 'estimated_minutes' => 2,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 3, 'step_type' => 'guided_practice',
            'title' => '오행 5글자 써보기',
            'content_markdown' => '木 火 土 金 水 다섯 글자를 직접 따라 써보세요. 획순보다 전체적인 모양의 흐름에 집중하면 됩니다.',
            'payload_json' => json_encode(['repeat_count' => 2]),
            'sort_order' => 8, 'is_required' => false, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];

        // ============================================================
        // 레슨 4: 천간 10글자 입문 (기존 2스텝 → 추가 4스텝)
        // ============================================================
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 4, 'step_type' => 'explanation',
            'title' => '천간의 오행·음양 짝 — 왜 2글자씩 묶일까',
            'content_markdown' => "천간 10글자는 오행별로 2글자씩 짝을 이룹니다. 첫 번째가 양(+), 두 번째가 음(-)입니다.\n\n| 오행 | 양(+) | 음(-) | 자연 비유 |\n|------|-------|-------|----------|\n| 木 | 甲 갑 | 乙 을 | 큰 나무 / 풀꽃·덩굴 |\n| 火 | 丙 병 | 丁 정 | 태양 / 달·촛불 |\n| 土 | 戊 무 | 己 기 | 큰 산 / 농밭 |\n| 金 | 庚 경 | 辛 신 | 쇳덩어리 / 보석·바늘 |\n| 水 | 壬 임 | 癸 계 | 바다·큰 강 / 이슬·빗방울 |\n\n양(+)은 크고 거칠고 외향적, 음(-)은 작고 정교하고 내향적인 성격을 띱니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 4, 'step_type' => 'explanation',
            'title' => '천간 10글자의 성격 특성',
            'content_markdown' => "각 천간은 사주에서 사람의 성향과 연결됩니다.\n\n**甲 갑**: 개척자. 시작과 전진, 진취적이고 창조적\n**乙 을**: 조율자. 유연하고 적응력이 뛰어남\n**丙 병**: 표현자. 밝고 긍정적, 비전 제시에 강함\n**丁 정**: 분석가. 세밀하고 예리한 집중력\n**戊 무**: 포용자. 넓은 스케일, 안정감과 포용력\n**己 기**: 관리자. 내실을 다지며 현실적 조율에 능함\n**庚 경**: 혁신가. 시련을 견디는 강직함과 결단력\n**辛 신**: 완벽주의자. 정제된 미학과 날카로운 감각\n**壬 임**: 도전자. 경계를 넘어 확장하려는 모험 정신\n**癸 계**: 통찰자. 깊은 내면과 지혜, 조용한 관찰력",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 4, 'step_type' => 'explanation',
            'title' => '천간 기억법 — 자연 풍경으로 외우기',
            'content_markdown' => "10글자를 하나의 자연 풍경으로 연결하면 쉽게 기억됩니다.\n\n\"위로 곧게 뻗은 커다란 **나무(甲)**와 그 나무를 감싸며 피어나는 유연한 **풀꽃(乙)**이 있습니다.\n\n이 식물들은 눈부신 **태양(丙)**과 은은한 **달빛(丁)**을 받으며 자랍니다.\n\n뿌리를 내린 곳은 든든한 **큰 산(戊)**과 비옥한 **농밭(己)**입니다.\n\n흙을 파면 거친 **쇳덩어리(庚)**와 정교한 **보석(辛)**이 숨겨져 있습니다.\n\n이 모든 자연을 품어주는 거대한 **바다(壬)**가 흐르고, 하늘에서는 생명수인 **단비(癸)**가 내립니다.\"\n\n이 이야기를 떠올리면 갑을병정무기경신임계 순서가 자연스럽게 기억됩니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 5, 'is_required' => false, 'estimated_minutes' => 2,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 4, 'step_type' => 'quiz',
            'title' => '천간 구별 퀴즈',
            'content_markdown' => '천간의 오행과 음양을 연결해보는 퀴즈입니다.',
            'payload_json' => json_encode(['quiz_set_code' => 'QUIZ_STEM_001']),
            'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];

        // ============================================================
        // 레슨 5: 지지 12글자 입문 (기존 2스텝 → 추가 4스텝)
        // ============================================================
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 5, 'step_type' => 'explanation',
            'title' => '지지와 12띠 동물 — 시간·계절·방위',
            'content_markdown' => "지지 12글자는 각각 동물, 시간대, 계절, 방위와 대응됩니다.\n\n| 지지 | 동물 | 시간 | 월(양력) | 계절 | 오행 |\n|------|------|------|---------|------|------|\n| 子 자 | 쥐 | 23:30~01:30 | 12월 | 겨울 | 水 |\n| 丑 축 | 소 | 01:30~03:30 | 1월 | 겨울 | 土 |\n| 寅 인 | 호랑이 | 03:30~05:30 | 2월 | 봄 | 木 |\n| 卯 묘 | 토끼 | 05:30~07:30 | 3월 | 봄 | 木 |\n| 辰 진 | 용 | 07:30~09:30 | 4월 | 봄 | 土 |\n| 巳 사 | 뱀 | 09:30~11:30 | 5월 | 여름 | 火 |\n| 午 오 | 말 | 11:30~13:30 | 6월 | 여름 | 火 |\n| 未 미 | 양 | 13:30~15:30 | 7월 | 여름 | 土 |\n| 申 신 | 원숭이 | 15:30~17:30 | 8월 | 가을 | 金 |\n| 酉 유 | 닭 | 17:30~19:30 | 9월 | 가을 | 金 |\n| 戌 술 | 개 | 19:30~21:30 | 10월 | 가을 | 土 |\n| 亥 해 | 돼지 | 21:30~23:30 | 11월 | 겨울 | 水 |",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 5, 'step_type' => 'explanation',
            'title' => '방합 기억법 — 계절과 방위로 3글자씩 묶기',
            'content_markdown' => "12글자를 순서대로만 외우면 어렵습니다. **계절별 3글자씩 묶어서** 풍경을 상상하면 쉽습니다.\n\n🌳 **봄 · 동쪽 · 목(木)**: 寅卯辰 (인묘진)\n→ 따뜻한 봄날 동쪽 숲에서 **호랑이, 토끼, 용**이 뛰놉니다.\n\n🔥 **여름 · 남쪽 · 화(火)**: 巳午未 (사오미)\n→ 뜨거운 여름 남쪽 들판에서 **뱀이 기어가고 말과 양**이 땀을 흘립니다.\n\n🪨 **가을 · 서쪽 · 금(金)**: 申酉戌 (신유술)\n→ 서늘한 가을 서쪽 마을에서 **원숭이가 열매 따고 닭과 개**가 수확을 지킵니다.\n\n💧 **겨울 · 북쪽 · 수(水)**: 亥子丑 (해자축)\n→ 추운 겨울 북쪽 마당에서 **돼지, 쥐, 소**가 체온을 나누며 쉽니다.\n\n이 4가지 그림만 기억하면 순서, 계절, 방위, 오행이 한 번에 연결됩니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 5, 'step_type' => 'explanation',
            'title' => '12지지의 성격 특성',
            'content_markdown' => "각 지지는 사주에서 사람의 내면 성향과 연결됩니다.\n\n**子 자(쥐)**: 영리하고 눈치가 빠름, 사교적이지만 내면에 불안감\n**丑 축(소)**: 묵묵하고 성실한 노력파, 느리지만 끈기 있음\n**寅 인(호랑이)**: 카리스마 있는 리더, 행동력과 결단력\n**卯 묘(토끼)**: 유연한 생존력, 예술적 감각과 매력\n**辰 진(용)**: 스케일이 크고 야망이 높음, 변화무쌍\n**巳 사(뱀)**: 지적이고 직감이 날카로움, 비밀스러운 면\n**午 오(말)**: 열정적이고 활동적, 자존심이 강함\n**未 미(양)**: 온순하고 다정, 감수성이 풍부\n**申 신(원숭이)**: 머리 회전이 빠르고 다재다능, 유머 감각\n**酉 유(닭)**: 섬세하고 예리한 완벽주의자, 언변이 뛰어남\n**戌 술(개)**: 의리와 충성, 정의감이 강하지만 의심 많음\n**亥 해(돼지)**: 관대하고 순수, 재물복이 있으며 몰입형",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 5, 'step_type' => 'quiz',
            'title' => '지지 기본 퀴즈',
            'content_markdown' => '지지를 글자/동물/오행으로 연결하는 퀴즈입니다.',
            'payload_json' => json_encode(['quiz_set_code' => 'QUIZ_BRANCH_001']),
            'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];

        // ============================================================
        // 레슨 6: 만세력 첫 읽기 (기존 2스텝 → 추가 4스텝)
        // ============================================================
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 6, 'step_type' => 'explanation',
            'title' => '사주팔자란? — 네 기둥, 여덟 글자',
            'content_markdown' => "사주팔자(四柱八字)는 **'네 개의 기둥(四柱)'**과 **'여덟 개의 글자(八字)'**로 사람의 운명을 나타낸 암호입니다.\n\n만세력 표를 보면 한자가 **위아래 2줄**, **가로 4칸**으로 총 8글자가 배치됩니다.\n\n```\n  시주   일주   월주   연주\n┌──────┬──────┬──────┬──────┐\n│ 시간  │ 일간  │ 월간  │ 연간  │  ← 천간 (하늘)\n├──────┼──────┼──────┼──────┤\n│ 시지  │ 일지  │ 월지  │ 연지  │  ← 지지 (땅)\n└──────┴──────┴──────┴──────┘\n```\n\n**위쪽 줄(천간)**: 하늘의 기운, 겉으로 드러나는 성격\n**아래쪽 줄(지지)**: 땅의 기운, 내면의 기질과 현실",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 3, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 6, 'step_type' => 'explanation',
            'title' => '네 기둥의 의미 — 근묘화실',
            'content_markdown' => "네 기둥은 각각 인생의 다른 시기와 영역을 나타냅니다. 이를 **근묘화실(根苗花實)** — 뿌리, 싹, 꽃, 열매에 비유합니다.\n\n**연주(年柱)** 🌱 뿌리 · 초년운\n태어난 해. 조상, 가문, 어린 시절의 환경\n\n**월주(月柱)** 🌿 싹 · 청중년운\n태어난 달. 부모, 형제, 직업 환경, 사회적 위치\n\n**일주(日柱)** 🌸 꽃 · 본인\n태어난 날. **나 자신**과 배우자. 평생 가장 강력한 영향\n\n**시주(時柱)** 🍎 열매 · 말년운\n태어난 시간. 자식, 말년의 삶, 인생의 결과물",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 4, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 6, 'step_type' => 'explanation',
            'title' => '일간(日干) — 사주의 주인공, 바로 "나"',
            'content_markdown' => "8글자 중 가장 먼저 찾아야 할 글자는 **일간(日干)**입니다.\n\n일간 = 일주(태어난 날)의 **위쪽 글자(천간)**\n\n이것이 중요한 이유는 사주에서 **'나 자신'**을 상징하기 때문입니다.\n\n나머지 7글자는 모두 이 일간을 기준으로 \"나와 어떤 관계인가?\"를 따져서 해석합니다.\n\n예를 들어:\n- 일간이 **甲(갑, 양목)**이면 → 나는 커다란 나무 같은 성향\n- 일간이 **丁(정, 음화)**이면 → 나는 촛불처럼 섬세하고 예리한 성향\n\n만세력을 처음 열면 **\"내 일간은 뭐지?\"**부터 확인하세요. 그게 사주 읽기의 첫걸음입니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 5, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $newSteps[] = [
            'id' => $id++, 'lesson_id' => 6, 'step_type' => 'explanation',
            'title' => '처음 8글자 읽는 순서',
            'content_markdown' => "만세력을 처음 볼 때 이 순서로 읽어보세요.\n\n**1단계: 일간 찾기** 🎯\n오른쪽에서 세 번째 칸, 위쪽 글자. 나의 오행과 음양을 파악합니다.\n\n**2단계: 오행 색깔 분포 보기** 🎨\n8글자 전체에 목(초록)·화(빨강)·토(노랑)·금(흰색)·수(검정)이 어떻게 분포되어 있는지 봅니다. 특정 오행이 너무 많거나 하나도 없으면 기운이 쏠려 있다는 뜻입니다.\n\n**3단계: 시간 흐름 읽기** ⏳\n연주(초년) → 월주(중년) → 일주(나) → 시주(말년) 순서로 어떤 기운이 흐르는지 큰 그림을 그려봅니다.\n\n이 3단계만으로도 \"내 사주는 대충 이런 느낌이구나\"를 감잡을 수 있습니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => 6, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];

        DB::table('lesson_steps')->insert($newSteps);
    }
}

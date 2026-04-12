<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManselyeokEnrichSeeder extends Seeder
{
    public function run(): void
    {
        $maxId = DB::table('lesson_steps')->max('id') ?? 0;
        $id = $maxId + 1;

        // 레슨 6 (LESSON_CHART_001) 에 스텝 추가
        $lessonId = DB::table('lessons')->where('code', 'LESSON_CHART_001')->value('id');
        if (!$lessonId) return;

        // 기존 스텝의 max sort_order 확인
        $maxSort = DB::table('lesson_steps')->where('lesson_id', $lessonId)->max('sort_order') ?? 0;
        $sort = $maxSort + 1;

        $steps = [];

        $steps[] = [
            'id' => $id++, 'lesson_id' => $lessonId, 'step_type' => 'explanation',
            'title' => '만세력이란? — 우주의 시간표',
            'content_markdown' => "**만세력(萬歲曆)**은 양력이나 음력 달력이 아니라, **60갑자(간지)를 바탕으로 생년월일시를 사주팔자로 변환해 보여주는 특수한 달력**입니다.\n\n옛날에는 두꺼운 책을 넘기며 직접 계산했지만, 요즘은 앱이나 웹사이트에 생년월일시만 입력하면 누구나 자신의 사주팔자를 뽑아볼 수 있습니다.\n\n만세력을 열면 보이는 주요 영역:\n\n1️⃣ **사주 원국(原局) 표**: 8글자가 배열된 핵심 영역\n2️⃣ **대운(大運) 표**: 10년 단위 운의 흐름\n3️⃣ **세운(歲運)**: 올해의 운\n4️⃣ **부가 정보**: 십신, 12운성, 신살, 지장간 등",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => $sort++, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];

        $steps[] = [
            'id' => $id++, 'lesson_id' => $lessonId, 'step_type' => 'explanation',
            'title' => '절기력 — 왜 양력/음력이 아닌 절기 기준인가',
            'content_markdown' => "사주에서 가장 헷갈리는 부분이 **\"왜 내 생일의 달과 만세력의 월이 다르지?\"**입니다.\n\n명리학은 인간이 만든 달력이 아니라, **태양의 고도와 지구의 위치를 절대적으로 반영한 절기력(24절기)**을 사용합니다.\n\n**연(年)의 기준**: 양력 1/1도, 음력 1/1도 아닌 **입춘(立春, 양력 2월 4일경)**\n→ 입춘 전에 태어나면 전년도 간지를 사용\n\n**월(月)의 기준**: 매월 1일이 아니라 **해당 월의 절기**가 들어오는 시점\n→ 절기의 시각(시·분 단위)까지 정확히 따짐\n\n이것이 중요한 이유: 사주는 내가 태어난 순간의 **우주적 시간 좌표**를 다루기 때문에, 인간이 편의상 만든 달력이 아닌 자연 자체의 시간표를 써야 정확합니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => $sort++, 'is_required' => true, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];

        $steps[] = [
            'id' => $id++, 'lesson_id' => $lessonId, 'step_type' => 'explanation',
            'title' => '월별 절기와 지지 매핑',
            'content_markdown' => "각 월의 시작은 해당 절기가 들어오는 시점입니다.\n\n| 월 | 지지 | 시작 절기 | 시기(양력) | 계절 |\n|----|------|---------|-----------|------|\n| 1월 | 寅 인 | **입춘**(立春) | 2월 초 | 봄 |\n| 2월 | 卯 묘 | **경칩**(驚蟄) | 3월 초 | 봄 |\n| 3월 | 辰 진 | **청명**(淸明) | 4월 초 | 봄 |\n| 4월 | 巳 사 | **입하**(立夏) | 5월 초 | 여름 |\n| 5월 | 午 오 | **망종**(芒種) | 6월 초 | 여름 |\n| 6월 | 未 미 | **소서**(小暑) | 7월 초 | 여름 |\n| 7월 | 申 신 | **입추**(立秋) | 8월 초 | 가을 |\n| 8월 | 酉 유 | **백로**(白露) | 9월 초 | 가을 |\n| 9월 | 戌 술 | **한로**(寒露) | 10월 초 | 가을 |\n| 10월 | 亥 해 | **입동**(立冬) | 11월 초 | 겨울 |\n| 11월 | 子 자 | **대설**(大雪) | 12월 초 | 겨울 |\n| 12월 | 丑 축 | **소한**(小寒) | 1월 초 | 겨울 |\n\n💡 사주의 1월은 寅(인)월, 즉 **양력 2월**부터 시작합니다. 양력 1월생은 사주상 **전년도 12월(丑월)**에 해당할 수 있습니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => $sort++, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];

        $steps[] = [
            'id' => $id++, 'lesson_id' => $lessonId, 'step_type' => 'explanation',
            'title' => '4주가 결정되는 방법',
            'content_markdown' => "사주의 네 기둥은 각각 다른 방법으로 결정됩니다.\n\n**연주(年柱)** — 입춘 기준\n입춘이 들어오는 정확한 시점을 지나야 새해의 간지 부여.\n예: 2024년 입춘은 2/4 16:27 → 이 시각 이후 출생 = 갑진(甲辰)년\n\n**월주(月柱)** — 절기 기준\n각 월의 절기가 들어오는 시·분 단위까지 정확히 적용.\n천간은 연간(年干)에 따른 공식으로 결정 (월두법/오호둔갑).\n\n**일주(日柱)** — 60갑자 순환\n갑자(甲子)부터 계해(癸亥)까지 하루에 하나씩 독립적으로 순환.\n만세력에서 해당 날짜의 간지를 그대로 찾으면 됨.\n\n**시주(時柱)** — 12시진\n하루 24시간을 2시간 단위로 12등분.\n지지는 시간으로 고정, 천간은 일간(日干)에 따라 결정 (시두법).\n\n⚠️ 한국은 동경 135도 표준시를 사용하여 실제 태양시와 약 30분 차이.\n→ 자시(子時)가 23:30~01:30으로 30분씩 밀려 적용.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => $sort++, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];

        $steps[] = [
            'id' => $id++, 'lesson_id' => $lessonId, 'step_type' => 'explanation',
            'title' => '만세력 화면에서 부가 정보 읽기',
            'content_markdown' => "만세력 앱을 열면 8글자 외에도 다양한 정보가 표시됩니다.\n\n**① 십신(十神)** — 글자 위/아래 작은 글씨\n식신, 정재, 정관, 편인 등. 일간(나) 기준으로 각 글자가 나와 맺는 관계.\n→ 성격, 직업, 대인관계를 구체적으로 읽는 핵심 지표\n\n**② 지장간(地藏干)** — 지지 아래 2~3글자\n지지 속에 숨어 있는 천간의 기운.\n→ 겉과 다른 숨겨진 성향이나 잠재력\n\n**③ 12운성** — 장생, 제왕, 묘 등\n오행 에너지의 생애 주기(탄생~소멸~재탄생)를 12단계로 표시.\n→ 각 기운이 지금 얼마나 강한지 가늠\n\n**④ 대운(大運) 표** — 원국 아래 가로 배열\n10년 단위 간지 + 대운수(시작 나이).\n→ 현재 나이에 해당하는 대운 확인\n\n**⑤ 신살** — 도화살, 역마살, 화개살 등\n사주의 디테일을 잡아주는 보조 도구.\n→ 매력, 이동, 학문 등 구체적 특성\n\n💡 처음에는 **일간 → 오행 분포 → 근묘화실 → 십신** 순서로만 보세요. 나머지는 차차 눈에 들어옵니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => $sort++, 'is_required' => true, 'estimated_minutes' => 4,
            'created_at' => now(), 'updated_at' => now(),
        ];

        $steps[] = [
            'id' => $id++, 'lesson_id' => $lessonId, 'step_type' => 'explanation',
            'title' => '24절기 전체 목록',
            'content_markdown' => "사주의 달력인 24절기 전체 목록입니다. **절기(節)**가 월의 시작을 결정합니다.\n\n| 계절 | 절기(節) — 월 시작 | 중기(中) |\n|------|------------------|----------|\n| 🌱 봄 | **입춘**(立春) 2/4경 | 우수(雨水) 2/19경 |\n| | **경칩**(驚蟄) 3/6경 | 춘분(春分) 3/21경 |\n| | **청명**(淸明) 4/5경 | 곡우(穀雨) 4/20경 |\n| ☀️ 여름 | **입하**(立夏) 5/6경 | 소만(小滿) 5/21경 |\n| | **망종**(芒種) 6/6경 | 하지(夏至) 6/21경 |\n| | **소서**(小暑) 7/7경 | 대서(大暑) 7/23경 |\n| 🍂 가을 | **입추**(立秋) 8/7경 | 처서(處暑) 8/23경 |\n| | **백로**(白露) 9/8경 | 추분(秋分) 9/23경 |\n| | **한로**(寒露) 10/8경 | 상강(霜降) 10/23경 |\n| ❄️ 겨울 | **입동**(立冬) 11/7경 | 소설(小雪) 11/22경 |\n| | **대설**(大雪) 12/7경 | 동지(冬至) 12/22경 |\n| | **소한**(小寒) 1/6경 | 대한(大寒) 1/20경 |\n\n절기(節)는 각 월의 **시작**을, 중기(中)는 월의 **중간**을 나타냅니다.\n사주에서 월주를 정할 때는 절기(節)만 기준으로 삼습니다.",
            'payload_json' => json_encode(['display_mode' => 'text']),
            'sort_order' => $sort++, 'is_required' => false, 'estimated_minutes' => 3,
            'created_at' => now(), 'updated_at' => now(),
        ];

        DB::table('lesson_steps')->insert($steps);

        // 레슨의 소요시간도 업데이트
        DB::table('lessons')->where('id', $lessonId)->update([
            'estimated_minutes' => 30,
        ]);
    }
}

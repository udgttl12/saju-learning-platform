<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HanjaCharSeeder extends Seeder
{
    public function run(): void
    {
        // hanja_chars: 오행 5 + 천간 10 + 지지 12 = 27건
        DB::table('hanja_chars')->insert([
            // 오행 (Five Elements)
            ['id' => 1, 'char_value' => '木', 'slug' => 'mok', 'reading_ko' => '목', 'meaning_ko' => '나무', 'category' => 'five_elements', 'element' => 'wood', 'yin_yang' => 'neutral', 'structure_note' => '세로줄과 가지가 뻗는 구조', 'mnemonic_text' => '줄기와 가지가 뻗는 모양으로 보면 기억이 쉽다.', 'usage_in_saju' => '오행 중 목. 성장, 시작, 확장의 이미지와 연결된다.', 'stroke_count' => 4, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'char_value' => '火', 'slug' => 'hwa', 'reading_ko' => '화', 'meaning_ko' => '불', 'category' => 'five_elements', 'element' => 'fire', 'yin_yang' => 'neutral', 'structure_note' => '불꽃이 퍼지는 구조', 'mnemonic_text' => '위로 튀는 불꽃처럼 보면서 기억한다.', 'usage_in_saju' => '오행 중 화. 열기, 표현, 확산의 이미지와 연결된다.', 'stroke_count' => 4, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'char_value' => '土', 'slug' => 'to', 'reading_ko' => '토', 'meaning_ko' => '흙', 'category' => 'five_elements', 'element' => 'earth', 'yin_yang' => 'neutral', 'structure_note' => '수직 중심이 있는 안정 구조', 'mnemonic_text' => '가운데를 받쳐주는 땅이라고 떠올리면 쉽다.', 'usage_in_saju' => '오행 중 토. 중재, 완충, 기반의 이미지와 연결된다.', 'stroke_count' => 3, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'char_value' => '金', 'slug' => 'geum', 'reading_ko' => '금', 'meaning_ko' => '쇠', 'category' => 'five_elements', 'element' => 'metal', 'yin_yang' => 'neutral', 'structure_note' => '단단한 구조와 점획이 섞인 형태', 'mnemonic_text' => '광물 덩어리처럼 단단한 이미지로 기억한다.', 'usage_in_saju' => '오행 중 금. 절제, 결단, 정리의 이미지와 연결된다.', 'stroke_count' => 8, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'char_value' => '水', 'slug' => 'su', 'reading_ko' => '수', 'meaning_ko' => '물', 'category' => 'five_elements', 'element' => 'water', 'yin_yang' => 'neutral', 'structure_note' => '흐르는 형태의 획', 'mnemonic_text' => '흘러내리는 물줄기처럼 보면 기억이 쉽다.', 'usage_in_saju' => '오행 중 수. 흐름, 지혜, 저장, 순환의 이미지와 연결된다.', 'stroke_count' => 4, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],

            // 천간 (Heavenly Stems)
            ['id' => 6, 'char_value' => '甲', 'slug' => 'gap', 'reading_ko' => '갑', 'meaning_ko' => '갑옷, 시작의 갑', 'category' => 'heavenly_stems', 'element' => 'wood', 'yin_yang' => 'yang', 'structure_note' => '단단한 뚜껑이 있는 형태', 'mnemonic_text' => '씨앗 껍질을 깨고 나오는 시작 에너지로 외운다.', 'usage_in_saju' => '천간 1. 양목. 시작, 기획, 전진의 이미지.', 'stroke_count' => 5, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'char_value' => '乙', 'slug' => 'eul', 'reading_ko' => '을', 'meaning_ko' => '굽은 새싹의 을', 'category' => 'heavenly_stems', 'element' => 'wood', 'yin_yang' => 'yin', 'structure_note' => '굽은 한 획 중심', 'mnemonic_text' => '구부러지지만 꺾이지 않는 새싹으로 기억한다.', 'usage_in_saju' => '천간 2. 음목. 유연함, 연결, 조율의 이미지.', 'stroke_count' => 1, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'char_value' => '丙', 'slug' => 'byeong', 'reading_ko' => '병', 'meaning_ko' => '밝게 드러나는 병', 'category' => 'heavenly_stems', 'element' => 'fire', 'yin_yang' => 'yang', 'structure_note' => '위아래로 열리는 형태', 'mnemonic_text' => '햇빛이 크게 드러나는 이미지로 기억한다.', 'usage_in_saju' => '천간 3. 양화. 외향적 표현, 가시성, 확산.', 'stroke_count' => 5, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'char_value' => '丁', 'slug' => 'jeong', 'reading_ko' => '정', 'meaning_ko' => '촛불의 정', 'category' => 'heavenly_stems', 'element' => 'fire', 'yin_yang' => 'yin', 'structure_note' => '짧고 응축된 형태', 'mnemonic_text' => '작지만 선명한 불빛으로 기억한다.', 'usage_in_saju' => '천간 4. 음화. 섬세한 표현, 집중, 온기.', 'stroke_count' => 2, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'char_value' => '戊', 'slug' => 'mu', 'reading_ko' => '무', 'meaning_ko' => '큰 흙의 무', 'category' => 'heavenly_stems', 'element' => 'earth', 'yin_yang' => 'yang', 'structure_note' => '가운데가 묵직한 구조', 'mnemonic_text' => '버팀목 같은 큰 땅으로 외운다.', 'usage_in_saju' => '천간 5. 양토. 큰 틀, 중심, 관리의 이미지.', 'stroke_count' => 5, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'char_value' => '己', 'slug' => 'gi', 'reading_ko' => '기', 'meaning_ko' => '몸을 굽힌 기', 'category' => 'heavenly_stems', 'element' => 'earth', 'yin_yang' => 'yin', 'structure_note' => '구부러진 형태', 'mnemonic_text' => '손으로 다듬는 흙처럼 세밀한 토로 기억한다.', 'usage_in_saju' => '천간 6. 음토. 조정, 실무, 섬세한 균형.', 'stroke_count' => 3, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'char_value' => '庚', 'slug' => 'gyeong', 'reading_ko' => '경', 'meaning_ko' => '날을 세우는 경', 'category' => 'heavenly_stems', 'element' => 'metal', 'yin_yang' => 'yang', 'structure_note' => '각진 중심 구조', 'mnemonic_text' => '단단한 금속 공구 이미지를 붙이면 쉽다.', 'usage_in_saju' => '천간 7. 양금. 절단, 결단, 추진의 이미지.', 'stroke_count' => 8, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'char_value' => '辛', 'slug' => 'sin', 'reading_ko' => '신', 'meaning_ko' => '보석 같은 신', 'category' => 'heavenly_stems', 'element' => 'metal', 'yin_yang' => 'yin', 'structure_note' => '뾰족하고 정제된 구조', 'mnemonic_text' => '정교하게 가공된 금속/보석으로 기억한다.', 'usage_in_saju' => '천간 8. 음금. 세밀함, 품질, 정교함.', 'stroke_count' => 7, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'char_value' => '壬', 'slug' => 'im', 'reading_ko' => '임', 'meaning_ko' => '큰 물의 임', 'category' => 'heavenly_stems', 'element' => 'water', 'yin_yang' => 'yang', 'structure_note' => '넓게 펼쳐진 물의 느낌', 'mnemonic_text' => '큰 강이나 바다처럼 넓은 물로 외운다.', 'usage_in_saju' => '천간 9. 양수. 유통, 확장, 큰 흐름.', 'stroke_count' => 4, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'char_value' => '癸', 'slug' => 'gye', 'reading_ko' => '계', 'meaning_ko' => '비나 이슬의 계', 'category' => 'heavenly_stems', 'element' => 'water', 'yin_yang' => 'yin', 'structure_note' => '빗방울이 맺히는 구조', 'mnemonic_text' => '새벽 이슬처럼 작고 세밀한 물로 기억한다.', 'usage_in_saju' => '천간 10. 음수. 저장, 침잠, 세밀한 지혜.', 'stroke_count' => 9, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],

            // 지지 (Earthly Branches)
            ['id' => 16, 'char_value' => '子', 'slug' => 'ja', 'reading_ko' => '자', 'meaning_ko' => '자, 씨앗의 자', 'category' => 'earthly_branches', 'element' => 'water', 'yin_yang' => 'yang', 'structure_note' => '단순하지만 시작점이 있는 형태', 'mnemonic_text' => '작은 씨앗과 한밤의 시작점으로 기억한다.', 'usage_in_saju' => '지지 1. 자수. 물, 시작, 밤의 문턱.', 'stroke_count' => 3, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'char_value' => '丑', 'slug' => 'chuk', 'reading_ko' => '축', 'meaning_ko' => '소 축', 'category' => 'earthly_branches', 'element' => 'earth', 'yin_yang' => 'yin', 'structure_note' => '굽은 획과 받침이 있는 형태', 'mnemonic_text' => '차분하게 버티는 흙과 겨울의 축적을 연결한다.', 'usage_in_saju' => '지지 2. 축토. 저장, 완만함, 겨울 끝자락.', 'stroke_count' => 4, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'char_value' => '寅', 'slug' => 'in', 'reading_ko' => '인', 'meaning_ko' => '호랑이 인', 'category' => 'earthly_branches', 'element' => 'wood', 'yin_yang' => 'yang', 'structure_note' => '집과 뻗는 기세가 있는 형태', 'mnemonic_text' => '봄이 튀어나오는 문이 열리는 느낌으로 기억한다.', 'usage_in_saju' => '지지 3. 인목. 개시, 출발, 봄의 기세.', 'stroke_count' => 11, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'char_value' => '卯', 'slug' => 'myo', 'reading_ko' => '묘', 'meaning_ko' => '토끼 묘', 'category' => 'earthly_branches', 'element' => 'wood', 'yin_yang' => 'yin', 'structure_note' => '좌우로 열린 구조', 'mnemonic_text' => '새싹이 균형 있게 펴지는 느낌으로 외운다.', 'usage_in_saju' => '지지 4. 묘목. 순수 성장, 봄의 확장.', 'stroke_count' => 5, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'char_value' => '辰', 'slug' => 'jin', 'reading_ko' => '진', 'meaning_ko' => '용 진', 'category' => 'earthly_branches', 'element' => 'earth', 'yin_yang' => 'yang', 'structure_note' => '길게 이어지는 구조', 'mnemonic_text' => '창고가 열리고 움직이는 흙으로 기억한다.', 'usage_in_saju' => '지지 5. 진토. 전환, 저장고, 변곡점.', 'stroke_count' => 7, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'char_value' => '巳', 'slug' => 'sa', 'reading_ko' => '사', 'meaning_ko' => '뱀 사', 'category' => 'earthly_branches', 'element' => 'fire', 'yin_yang' => 'yin', 'structure_note' => '몸을 감은 듯한 짧은 구조', 'mnemonic_text' => '불기운이 말려 올라가는 뱀처럼 기억한다.', 'usage_in_saju' => '지지 6. 사화. 열기 상승, 초여름.', 'stroke_count' => 3, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'char_value' => '午', 'slug' => 'o', 'reading_ko' => '오', 'meaning_ko' => '말 오', 'category' => 'earthly_branches', 'element' => 'fire', 'yin_yang' => 'yang', 'structure_note' => '가운데가 뻗는 구조', 'mnemonic_text' => '정오의 한가운데 태양처럼 기억한다.', 'usage_in_saju' => '지지 7. 오화. 절정, 정점, 한낮의 불.', 'stroke_count' => 4, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'char_value' => '未', 'slug' => 'mi', 'reading_ko' => '미', 'meaning_ko' => '양 미', 'category' => 'earthly_branches', 'element' => 'earth', 'yin_yang' => 'yin', 'structure_note' => '가늘지만 받치는 구조', 'mnemonic_text' => '열기를 받아 정리하는 흙으로 기억한다.', 'usage_in_saju' => '지지 8. 미토. 완충, 조정, 여름 끝자락.', 'stroke_count' => 5, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'char_value' => '申', 'slug' => 'sin-branch', 'reading_ko' => '신', 'meaning_ko' => '원숭이 신', 'category' => 'earthly_branches', 'element' => 'metal', 'yin_yang' => 'yang', 'structure_note' => '여러 획이 접히는 구조', 'mnemonic_text' => '금속이 드러나기 시작하는 가을 문턱으로 외운다.', 'usage_in_saju' => '지지 9. 신금. 이동, 시작되는 금기운.', 'stroke_count' => 5, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'char_value' => '酉', 'slug' => 'yu', 'reading_ko' => '유', 'meaning_ko' => '닭 유', 'category' => 'earthly_branches', 'element' => 'metal', 'yin_yang' => 'yin', 'structure_note' => '닫힌 그릇 형태', 'mnemonic_text' => '정제되어 응축된 금속/가을 수확으로 기억한다.', 'usage_in_saju' => '지지 10. 유금. 정제, 결실, 수확.', 'stroke_count' => 7, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'char_value' => '戌', 'slug' => 'sul', 'reading_ko' => '술', 'meaning_ko' => '개 술', 'category' => 'earthly_branches', 'element' => 'earth', 'yin_yang' => 'yang', 'structure_note' => '건조하게 정리되는 형태', 'mnemonic_text' => '문을 닫고 정리하는 가을 끝 흙으로 기억한다.', 'usage_in_saju' => '지지 11. 술토. 마감, 정리, 건조한 흙.', 'stroke_count' => 6, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'char_value' => '亥', 'slug' => 'hae', 'reading_ko' => '해', 'meaning_ko' => '돼지 해', 'category' => 'earthly_branches', 'element' => 'water', 'yin_yang' => 'yin', 'structure_note' => '부드럽게 감싸는 구조', 'mnemonic_text' => '겨울로 넘어가는 깊은 물문으로 기억한다.', 'usage_in_saju' => '지지 12. 해수. 저장, 큰 물의 시작.', 'stroke_count' => 6, 'is_core' => true, 'publish_status' => 'published', 'published_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        // hanja_group_links: 37건
        DB::table('hanja_group_links')->insert([
            // 오행 -> group 1
            ['id' => 1, 'hanja_char_id' => 1, 'hanja_group_id' => 1, 'sort_order' => 1],
            ['id' => 2, 'hanja_char_id' => 2, 'hanja_group_id' => 1, 'sort_order' => 2],
            ['id' => 3, 'hanja_char_id' => 3, 'hanja_group_id' => 1, 'sort_order' => 3],
            ['id' => 4, 'hanja_char_id' => 4, 'hanja_group_id' => 1, 'sort_order' => 4],
            ['id' => 5, 'hanja_char_id' => 5, 'hanja_group_id' => 1, 'sort_order' => 5],

            // 천간 -> group 2
            ['id' => 6, 'hanja_char_id' => 6, 'hanja_group_id' => 2, 'sort_order' => 1],
            ['id' => 7, 'hanja_char_id' => 7, 'hanja_group_id' => 2, 'sort_order' => 2],
            ['id' => 8, 'hanja_char_id' => 8, 'hanja_group_id' => 2, 'sort_order' => 3],
            ['id' => 9, 'hanja_char_id' => 9, 'hanja_group_id' => 2, 'sort_order' => 4],
            ['id' => 10, 'hanja_char_id' => 10, 'hanja_group_id' => 2, 'sort_order' => 5],
            ['id' => 11, 'hanja_char_id' => 11, 'hanja_group_id' => 2, 'sort_order' => 6],
            ['id' => 12, 'hanja_char_id' => 12, 'hanja_group_id' => 2, 'sort_order' => 7],
            ['id' => 13, 'hanja_char_id' => 13, 'hanja_group_id' => 2, 'sort_order' => 8],
            ['id' => 14, 'hanja_char_id' => 14, 'hanja_group_id' => 2, 'sort_order' => 9],
            ['id' => 15, 'hanja_char_id' => 15, 'hanja_group_id' => 2, 'sort_order' => 10],

            // 지지 -> group 3
            ['id' => 16, 'hanja_char_id' => 16, 'hanja_group_id' => 3, 'sort_order' => 1],
            ['id' => 17, 'hanja_char_id' => 17, 'hanja_group_id' => 3, 'sort_order' => 2],
            ['id' => 18, 'hanja_char_id' => 18, 'hanja_group_id' => 3, 'sort_order' => 3],
            ['id' => 19, 'hanja_char_id' => 19, 'hanja_group_id' => 3, 'sort_order' => 4],
            ['id' => 20, 'hanja_char_id' => 20, 'hanja_group_id' => 3, 'sort_order' => 5],
            ['id' => 21, 'hanja_char_id' => 21, 'hanja_group_id' => 3, 'sort_order' => 6],
            ['id' => 22, 'hanja_char_id' => 22, 'hanja_group_id' => 3, 'sort_order' => 7],
            ['id' => 23, 'hanja_char_id' => 23, 'hanja_group_id' => 3, 'sort_order' => 8],
            ['id' => 24, 'hanja_char_id' => 24, 'hanja_group_id' => 3, 'sort_order' => 9],
            ['id' => 25, 'hanja_char_id' => 25, 'hanja_group_id' => 3, 'sort_order' => 10],
            ['id' => 26, 'hanja_char_id' => 26, 'hanja_group_id' => 3, 'sort_order' => 11],
            ['id' => 27, 'hanja_char_id' => 27, 'hanja_group_id' => 3, 'sort_order' => 12],

            // 입문 핵심 카드 -> group 4
            ['id' => 28, 'hanja_char_id' => 1, 'hanja_group_id' => 4, 'sort_order' => 1],
            ['id' => 29, 'hanja_char_id' => 2, 'hanja_group_id' => 4, 'sort_order' => 2],
            ['id' => 30, 'hanja_char_id' => 3, 'hanja_group_id' => 4, 'sort_order' => 3],
            ['id' => 31, 'hanja_char_id' => 4, 'hanja_group_id' => 4, 'sort_order' => 4],
            ['id' => 32, 'hanja_char_id' => 5, 'hanja_group_id' => 4, 'sort_order' => 5],
            ['id' => 33, 'hanja_char_id' => 6, 'hanja_group_id' => 4, 'sort_order' => 6],

            // 만세력 읽기용 카드 -> group 5
            ['id' => 34, 'hanja_char_id' => 16, 'hanja_group_id' => 5, 'sort_order' => 1],
            ['id' => 35, 'hanja_char_id' => 6, 'hanja_group_id' => 5, 'sort_order' => 2],
            ['id' => 36, 'hanja_char_id' => 18, 'hanja_group_id' => 5, 'sort_order' => 3],
            ['id' => 37, 'hanja_char_id' => 22, 'hanja_group_id' => 5, 'sort_order' => 4],
        ]);

        // lesson_hanja_links: 31건
        DB::table('lesson_hanja_links')->insert([
            // Lesson 3 - 오행
            ['id' => 1, 'lesson_id' => 3, 'hanja_char_id' => 1, 'relation_type' => 'primary', 'sort_order' => 1],
            ['id' => 2, 'lesson_id' => 3, 'hanja_char_id' => 2, 'relation_type' => 'primary', 'sort_order' => 2],
            ['id' => 3, 'lesson_id' => 3, 'hanja_char_id' => 3, 'relation_type' => 'primary', 'sort_order' => 3],
            ['id' => 4, 'lesson_id' => 3, 'hanja_char_id' => 4, 'relation_type' => 'primary', 'sort_order' => 4],
            ['id' => 5, 'lesson_id' => 3, 'hanja_char_id' => 5, 'relation_type' => 'primary', 'sort_order' => 5],

            // Lesson 4 - 천간
            ['id' => 6, 'lesson_id' => 4, 'hanja_char_id' => 6, 'relation_type' => 'primary', 'sort_order' => 1],
            ['id' => 7, 'lesson_id' => 4, 'hanja_char_id' => 7, 'relation_type' => 'primary', 'sort_order' => 2],
            ['id' => 8, 'lesson_id' => 4, 'hanja_char_id' => 8, 'relation_type' => 'primary', 'sort_order' => 3],
            ['id' => 9, 'lesson_id' => 4, 'hanja_char_id' => 9, 'relation_type' => 'primary', 'sort_order' => 4],
            ['id' => 10, 'lesson_id' => 4, 'hanja_char_id' => 10, 'relation_type' => 'primary', 'sort_order' => 5],
            ['id' => 11, 'lesson_id' => 4, 'hanja_char_id' => 11, 'relation_type' => 'primary', 'sort_order' => 6],
            ['id' => 12, 'lesson_id' => 4, 'hanja_char_id' => 12, 'relation_type' => 'primary', 'sort_order' => 7],
            ['id' => 13, 'lesson_id' => 4, 'hanja_char_id' => 13, 'relation_type' => 'primary', 'sort_order' => 8],
            ['id' => 14, 'lesson_id' => 4, 'hanja_char_id' => 14, 'relation_type' => 'primary', 'sort_order' => 9],
            ['id' => 15, 'lesson_id' => 4, 'hanja_char_id' => 15, 'relation_type' => 'primary', 'sort_order' => 10],

            // Lesson 5 - 지지
            ['id' => 16, 'lesson_id' => 5, 'hanja_char_id' => 16, 'relation_type' => 'primary', 'sort_order' => 1],
            ['id' => 17, 'lesson_id' => 5, 'hanja_char_id' => 17, 'relation_type' => 'primary', 'sort_order' => 2],
            ['id' => 18, 'lesson_id' => 5, 'hanja_char_id' => 18, 'relation_type' => 'primary', 'sort_order' => 3],
            ['id' => 19, 'lesson_id' => 5, 'hanja_char_id' => 19, 'relation_type' => 'primary', 'sort_order' => 4],
            ['id' => 20, 'lesson_id' => 5, 'hanja_char_id' => 20, 'relation_type' => 'primary', 'sort_order' => 5],
            ['id' => 21, 'lesson_id' => 5, 'hanja_char_id' => 21, 'relation_type' => 'primary', 'sort_order' => 6],
            ['id' => 22, 'lesson_id' => 5, 'hanja_char_id' => 22, 'relation_type' => 'primary', 'sort_order' => 7],
            ['id' => 23, 'lesson_id' => 5, 'hanja_char_id' => 23, 'relation_type' => 'primary', 'sort_order' => 8],
            ['id' => 24, 'lesson_id' => 5, 'hanja_char_id' => 24, 'relation_type' => 'primary', 'sort_order' => 9],
            ['id' => 25, 'lesson_id' => 5, 'hanja_char_id' => 25, 'relation_type' => 'primary', 'sort_order' => 10],
            ['id' => 26, 'lesson_id' => 5, 'hanja_char_id' => 26, 'relation_type' => 'primary', 'sort_order' => 11],
            ['id' => 27, 'lesson_id' => 5, 'hanja_char_id' => 27, 'relation_type' => 'primary', 'sort_order' => 12],

            // Lesson 6 - example links
            ['id' => 28, 'lesson_id' => 6, 'hanja_char_id' => 6, 'relation_type' => 'example', 'sort_order' => 1],
            ['id' => 29, 'lesson_id' => 6, 'hanja_char_id' => 16, 'relation_type' => 'example', 'sort_order' => 2],
            ['id' => 30, 'lesson_id' => 6, 'hanja_char_id' => 8, 'relation_type' => 'example', 'sort_order' => 3],
            ['id' => 31, 'lesson_id' => 6, 'hanja_char_id' => 18, 'relation_type' => 'example', 'sort_order' => 4],
        ]);
    }
}

-- 사주입문플랫폼 | MySQL 8 MVP seed
-- 비밀번호: 아래 seed 계정은 모두 'password'
-- admin@example.com / demo@example.com

USE `saju_intro_platform`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `admin_audit_logs`;
TRUNCATE TABLE `bookmarks`;
TRUNCATE TABLE `review_logs`;
TRUNCATE TABLE `review_cards`;
TRUNCATE TABLE `lesson_attempts`;
TRUNCATE TABLE `quiz_items`;
TRUNCATE TABLE `quiz_sets`;
TRUNCATE TABLE `practice_strokes`;
TRUNCATE TABLE `practice_sessions`;
TRUNCATE TABLE `stroke_templates`;
TRUNCATE TABLE `lesson_hanja_links`;
TRUNCATE TABLE `hanja_group_links`;
TRUNCATE TABLE `hanja_chars`;
TRUNCATE TABLE `hanja_groups`;
TRUNCATE TABLE `lesson_steps`;
TRUNCATE TABLE `lessons`;
TRUNCATE TABLE `track_enrollments`;
TRUNCATE TABLE `learning_tracks`;
TRUNCATE TABLE `profiles`;
TRUNCATE TABLE `saju_examples`;
TRUNCATE TABLE `users`;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `users` (`id`, `email`, `password`, `role`, `status`, `email_verified_at`, `last_login_at`)
VALUES
  (1, 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW()),
  (2, 'demo@example.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member', 'active', NOW(), NOW());

INSERT INTO `profiles` (`id`, `user_id`, `display_name`, `beginner_level`, `hanja_level`, `daily_goal_minutes`, `preferred_learning_style`, `timezone`, `onboarding_completed_at`, `memo`)
VALUES
  (1, 1, '운영 관리자', 'returning', 'intermediate', 20, 'balanced', 'Asia/Seoul', NOW(), '콘텐츠/운영 관리자 seed'),
  (2, 2, '사주 입문러', 'absolute_beginner', 'none', 15, 'writing', 'Asia/Seoul', NOW(), '성인 취미 학습자 데모 계정');

INSERT INTO `learning_tracks` (`id`, `code`, `slug`, `title`, `short_description`, `target_audience`, `difficulty_level`, `estimated_total_minutes`, `sort_order`, `publish_status`, `published_at`, `created_by`, `updated_by`)
VALUES
  (1, 'TRACK_PREP', 'hanja-prep', '한자 준비운동', '획과 구조를 두려워하지 않도록 몸풀기부터 시작하는 트랙', 'adult_hobby_beginner', 1, 40, 1, 'published', NOW(), 1, 1),
  (2, 'TRACK_FIVE_ELEMENTS', 'five-elements', '오행 한자', '목·화·토·금·수 5글자를 먼저 익히는 핵심 트랙', 'adult_hobby_beginner', 1, 35, 2, 'published', NOW(), 1, 1),
  (3, 'TRACK_HEAVENLY_STEMS', 'heavenly-stems', '천간 한자', '갑을병정무기경신임계 10글자를 읽고 구분하는 트랙', 'adult_hobby_beginner', 2, 80, 3, 'published', NOW(), 1, 1),
  (4, 'TRACK_EARTHLY_BRANCHES', 'earthly-branches', '지지 한자', '자축인묘진사오미신유술해 12글자를 읽는 트랙', 'adult_hobby_beginner', 2, 90, 4, 'published', NOW(), 1, 1),
  (5, 'TRACK_CHART_READING', 'chart-reading', '만세력 첫 읽기', '연주·월주·일주·시주를 눈으로 읽는 첫 실전 트랙', 'adult_hobby_beginner', 2, 50, 5, 'published', NOW(), 1, 1);

INSERT INTO `track_enrollments` (`id`, `user_id`, `learning_track_id`, `status`, `progress_percent`, `started_at`, `last_accessed_at`)
VALUES
  (1, 2, 1, 'active', 50.00, NOW(), NOW()),
  (2, 2, 2, 'active', 20.00, NOW(), NOW());

INSERT INTO `lessons` (`id`, `learning_track_id`, `code`, `slug`, `title`, `objective`, `summary`, `lesson_type`, `difficulty_level`, `estimated_minutes`, `unlock_rule_json`, `sort_order`, `publish_status`, `published_at`, `created_by`, `updated_by`)
VALUES
  (1, 1, 'LESSON_PREP_001', 'hanja-structure-basics', '한자 구조와 획 맛보기', '한자를 처음 보는 학습자가 획과 구조를 낯설지 않게 느끼도록 한다.', '좌우형/상하형과 기본 획 감각을 잡는다.', 'concept', 1, 12, JSON_OBJECT('requires', JSON_ARRAY()), 1, 'published', NOW(), 1, 1),
  (2, 1, 'LESSON_PREP_002', 'basic-stroke-flow', '필순 감각 익히기', '정답 집착보다 큰 흐름으로 글자를 따라 쓰는 감각을 익힌다.', '쓰기 연습 전 준비 레슨', 'practice', 1, 10, JSON_OBJECT('requires', JSON_ARRAY('LESSON_PREP_001')), 2, 'published', NOW(), 1, 1),
  (3, 2, 'LESSON_FIVE_001', 'five-elements-overview', '오행 5글자 전체보기', '목·화·토·금·수 한자를 보고 사주 연결 이미지를 만든다.', '오행 5글자 입문 레슨', 'hanja_card', 1, 15, JSON_OBJECT('requires', JSON_ARRAY('LESSON_PREP_001')), 1, 'published', NOW(), 1, 1),
  (4, 3, 'LESSON_STEM_001', 'heavenly-stems-overview', '천간 10글자 입문', '갑을병정무기경신임계의 순서와 오행 연결을 읽는다.', '천간 전체 입문', 'hanja_card', 2, 18, JSON_OBJECT('requires', JSON_ARRAY('LESSON_FIVE_001')), 1, 'published', NOW(), 1, 1),
  (5, 4, 'LESSON_BRANCH_001', 'earthly-branches-overview', '지지 12글자 입문', '자축인묘진사오미신유술해를 동물·시간감각과 함께 익힌다.', '지지 전체 입문', 'hanja_card', 2, 20, JSON_OBJECT('requires', JSON_ARRAY('LESSON_STEM_001')), 1, 'published', NOW(), 1, 1),
  (6, 5, 'LESSON_CHART_001', 'four-pillars-first-look', '연주·월주·일주·시주 읽기', '만세력 표의 8글자를 보고 어디가 연/월/일/시인지 구분한다.', '첫 실전 입문', 'example_chart', 2, 15, JSON_OBJECT('requires', JSON_ARRAY('LESSON_BRANCH_001')), 1, 'published', NOW(), 1, 1);

INSERT INTO `lesson_steps` (`id`, `lesson_id`, `step_type`, `title`, `content_markdown`, `payload_json`, `sort_order`, `is_required`, `estimated_minutes`)
VALUES
  (1, 1, 'intro', '한자는 그림이 아니라 부품이다', '한자는 한 번에 통으로 외우기보다 **획 + 구조 + 반복 노출**로 익히는 것이 훨씬 쉽습니다.', JSON_OBJECT('cta', '다음 단계로'), 1, 1, 2),
  (2, 1, 'explanation', '좌우형과 상하형 감각 익히기', '좌우형은 양쪽으로, 상하형은 위아래로 무게가 나뉩니다. 글자의 **골격**을 먼저 본다고 생각하면 됩니다.', JSON_OBJECT('examples', JSON_ARRAY('木', '苗', '字')), 2, 1, 4),
  (3, 1, 'summary', '이 레슨의 핵심', '정확히 예쁘게 쓰기보다 **어디서 시작하고 어떻게 흘러가는지** 보는 습관을 만든다.', JSON_OBJECT('keywords', JSON_ARRAY('획', '구조', '반복')), 3, 1, 2),

  (4, 2, 'stroke_order', '큰 흐름으로 따라 쓰기', '정교한 필적 채점보다 **끊김 없이 흐름을 따라가는 것**에 집중합니다.', JSON_OBJECT('practice_mode', 'trace'), 1, 1, 3),
  (5, 2, 'guided_practice', '가이드 위에 써보기', '희미한 가이드 선을 따라 3번씩 써보세요.', JSON_OBJECT('repeat_count', 3), 2, 1, 5),

  (6, 3, 'intro', '오행은 사주의 색연필 통이다', '목·화·토·금·수는 뒤에서 천간과 지지를 읽을 때 계속 등장하는 핵심 분류입니다.', JSON_OBJECT('focus', JSON_ARRAY('木','火','土','金','水')), 1, 1, 2),
  (7, 3, 'explanation', '오행 5글자 연결하기', '글자를 외울 때는 **뜻 + 사주 역할 + 색감**을 같이 묶어두면 훨씬 오래 갑니다.', JSON_OBJECT('display_mode', 'card_grid'), 2, 1, 6),
  (8, 3, 'quiz', '오행 확인 퀴즈', '오행 5글자를 보고 뜻과 오행을 매칭합니다.', JSON_OBJECT('quiz_set_code', 'QUIZ_FIVE_001'), 3, 1, 4),

  (9, 4, 'intro', '천간 10글자는 하늘쪽 코드다', '갑을병정무기경신임계는 사주에서 **위줄**에 자주 보게 되는 핵심 글자입니다.', JSON_OBJECT('focus', JSON_ARRAY('甲','乙','丙','丁','戊','己','庚','辛','壬','癸')), 1, 1, 2),
  (10, 4, 'explanation', '오행과 음양 붙여 보기', '천간은 오행뿐 아니라 **음/양** 감각까지 같이 보면 기억이 빠릅니다.', JSON_OBJECT('display_mode', 'sequence'), 2, 1, 7),

  (11, 5, 'intro', '지지 12글자는 땅쪽 코드다', '자축인묘진사오미신유술해는 사주에서 **아래줄**에 자주 보게 되는 핵심 글자입니다.', JSON_OBJECT('focus', JSON_ARRAY('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥')), 1, 1, 2),
  (12, 5, 'explanation', '동물과 시간대 감각 붙이기', '지지는 동물·시간대·계절감을 같이 붙이면 기억이 훨씬 오래갑니다.', JSON_OBJECT('display_mode', 'grid'), 2, 1, 8),

  (13, 6, 'intro', '연월일시 먼저 자리를 읽는다', '해석보다 먼저, 표의 어느 칸이 연주·월주·일주·시주인지 위치를 읽을 줄 알아야 합니다.', JSON_OBJECT('sample_chart_code', 'EXAMPLE_CHART_001'), 1, 1, 3),
  (14, 6, 'summary', '오늘의 목표', '8글자를 무서워하지 않고 **칸과 글자**를 분리해서 보는 데 성공하면 됩니다.', JSON_OBJECT('keywords', JSON_ARRAY('연주','월주','일주','시주')), 2, 1, 2);

INSERT INTO `hanja_groups` (`id`, `group_type`, `code`, `name`, `description`, `sort_order`, `is_core`)
VALUES
  (1, 'category', 'FIVE_ELEMENTS', '오행', '목·화·토·금·수 분류', 1, 1),
  (2, 'category', 'HEAVENLY_STEMS', '천간', '갑을병정무기경신임계', 2, 1),
  (3, 'category', 'EARTHLY_BRANCHES', '지지', '자축인묘진사오미신유술해', 3, 1),
  (4, 'collection', 'BEGINNER_CORE', '입문 핵심 카드', '처음 보는 학습자가 반드시 익혀야 할 핵심 카드', 4, 1),
  (5, 'collection', 'CHART_READING', '만세력 읽기용 카드', '실전관에서 바로 쓰는 카드', 5, 1);

INSERT INTO `hanja_chars` (`id`, `char_value`, `slug`, `reading_ko`, `meaning_ko`, `category`, `element`, `yin_yang`, `structure_note`, `mnemonic_text`, `usage_in_saju`, `stroke_count`, `is_core`, `publish_status`, `published_at`)
VALUES
  (1, '木', 'mok', '목', '나무', 'five_elements', 'wood', 'neutral', '세로줄과 가지가 뻗는 구조', '줄기와 가지가 뻗는 모양으로 보면 기억이 쉽다.', '오행 중 목. 성장, 시작, 확장의 이미지와 연결된다.', 4, 1, 'published', NOW()),
  (2, '火', 'hwa', '화', '불', 'five_elements', 'fire', 'neutral', '불꽃이 퍼지는 구조', '위로 튀는 불꽃처럼 보면서 기억한다.', '오행 중 화. 열기, 표현, 확산의 이미지와 연결된다.', 4, 1, 'published', NOW()),
  (3, '土', 'to', '토', '흙', 'five_elements', 'earth', 'neutral', '수직 중심이 있는 안정 구조', '가운데를 받쳐주는 땅이라고 떠올리면 쉽다.', '오행 중 토. 중재, 완충, 기반의 이미지와 연결된다.', 3, 1, 'published', NOW()),
  (4, '金', 'geum', '금', '쇠', 'five_elements', 'metal', 'neutral', '단단한 구조와 점획이 섞인 형태', '광물 덩어리처럼 단단한 이미지로 기억한다.', '오행 중 금. 절제, 결단, 정리의 이미지와 연결된다.', 8, 1, 'published', NOW()),
  (5, '水', 'su', '수', '물', 'five_elements', 'water', 'neutral', '흐르는 형태의 획', '흘러내리는 물줄기처럼 보면 기억이 쉽다.', '오행 중 수. 흐름, 지혜, 저장, 순환의 이미지와 연결된다.', 4, 1, 'published', NOW()),

  (6, '甲', 'gap', '갑', '갑옷, 시작의 갑', 'heavenly_stems', 'wood', 'yang', '단단한 뚜껑이 있는 형태', '씨앗 껍질을 깨고 나오는 시작 에너지로 외운다.', '천간 1. 양목. 시작, 기획, 전진의 이미지.', 5, 1, 'published', NOW()),
  (7, '乙', 'eul', '을', '굽은 새싹의 을', 'heavenly_stems', 'wood', 'yin', '굽은 한 획 중심', '구부러지지만 꺾이지 않는 새싹으로 기억한다.', '천간 2. 음목. 유연함, 연결, 조율의 이미지.', 1, 1, 'published', NOW()),
  (8, '丙', 'byeong', '병', '밝게 드러나는 병', 'heavenly_stems', 'fire', 'yang', '위아래로 열리는 형태', '햇빛이 크게 드러나는 이미지로 기억한다.', '천간 3. 양화. 외향적 표현, 가시성, 확산.', 5, 1, 'published', NOW()),
  (9, '丁', 'jeong', '정', '촛불의 정', 'heavenly_stems', 'fire', 'yin', '짧고 응축된 형태', '작지만 선명한 불빛으로 기억한다.', '천간 4. 음화. 섬세한 표현, 집중, 온기.', 2, 1, 'published', NOW()),
  (10, '戊', 'mu', '무', '큰 흙의 무', 'heavenly_stems', 'earth', 'yang', '가운데가 묵직한 구조', '버팀목 같은 큰 땅으로 외운다.', '천간 5. 양토. 큰 틀, 중심, 관리의 이미지.', 5, 1, 'published', NOW()),
  (11, '己', 'gi', '기', '몸을 굽힌 기', 'heavenly_stems', 'earth', 'yin', '구부러진 형태', '손으로 다듬는 흙처럼 세밀한 토로 기억한다.', '천간 6. 음토. 조정, 실무, 섬세한 균형.', 3, 1, 'published', NOW()),
  (12, '庚', 'gyeong', '경', '날을 세우는 경', 'heavenly_stems', 'metal', 'yang', '각진 중심 구조', '단단한 금속 공구 이미지를 붙이면 쉽다.', '천간 7. 양금. 절단, 결단, 추진의 이미지.', 8, 1, 'published', NOW()),
  (13, '辛', 'sin', '신', '보석 같은 신', 'heavenly_stems', 'metal', 'yin', '뾰족하고 정제된 구조', '정교하게 가공된 금속/보석으로 기억한다.', '천간 8. 음금. 세밀함, 품질, 정교함.', 7, 1, 'published', NOW()),
  (14, '壬', 'im', '임', '큰 물의 임', 'heavenly_stems', 'water', 'yang', '넓게 펼쳐진 물의 느낌', '큰 강이나 바다처럼 넓은 물로 외운다.', '천간 9. 양수. 유통, 확장, 큰 흐름.', 4, 1, 'published', NOW()),
  (15, '癸', 'gye', '계', '비나 이슬의 계', 'heavenly_stems', 'water', 'yin', '빗방울이 맺히는 구조', '새벽 이슬처럼 작고 세밀한 물로 기억한다.', '천간 10. 음수. 저장, 침잠, 세밀한 지혜.', 9, 1, 'published', NOW()),

  (16, '子', 'ja', '자', '자, 씨앗의 자', 'earthly_branches', 'water', 'yang', '단순하지만 시작점이 있는 형태', '작은 씨앗과 한밤의 시작점으로 기억한다.', '지지 1. 자수. 물, 시작, 밤의 문턱.', 3, 1, 'published', NOW()),
  (17, '丑', 'chuk', '축', '소 축', 'earthly_branches', 'earth', 'yin', '굽은 획과 받침이 있는 형태', '차분하게 버티는 흙과 겨울의 축적을 연결한다.', '지지 2. 축토. 저장, 완만함, 겨울 끝자락.', 4, 1, 'published', NOW()),
  (18, '寅', 'in', '인', '호랑이 인', 'earthly_branches', 'wood', 'yang', '집과 뻗는 기세가 있는 형태', '봄이 튀어나오는 문이 열리는 느낌으로 기억한다.', '지지 3. 인목. 개시, 출발, 봄의 기세.', 11, 1, 'published', NOW()),
  (19, '卯', 'myo', '묘', '토끼 묘', 'earthly_branches', 'wood', 'yin', '좌우로 열린 구조', '새싹이 균형 있게 펴지는 느낌으로 외운다.', '지지 4. 묘목. 순수 성장, 봄의 확장.', 5, 1, 'published', NOW()),
  (20, '辰', 'jin', '진', '용 진', 'earthly_branches', 'earth', 'yang', '길게 이어지는 구조', '창고가 열리고 움직이는 흙으로 기억한다.', '지지 5. 진토. 전환, 저장고, 변곡점.', 7, 1, 'published', NOW()),
  (21, '巳', 'sa', '사', '뱀 사', 'earthly_branches', 'fire', 'yin', '몸을 감은 듯한 짧은 구조', '불기운이 말려 올라가는 뱀처럼 기억한다.', '지지 6. 사화. 열기 상승, 초여름.', 3, 1, 'published', NOW()),
  (22, '午', 'o', '오', '말 오', 'earthly_branches', 'fire', 'yang', '가운데가 뻗는 구조', '정오의 한가운데 태양처럼 기억한다.', '지지 7. 오화. 절정, 정점, 한낮의 불.', 4, 1, 'published', NOW()),
  (23, '未', 'mi', '미', '양 미', 'earthly_branches', 'earth', 'yin', '가늘지만 받치는 구조', '열기를 받아 정리하는 흙으로 기억한다.', '지지 8. 미토. 완충, 조정, 여름 끝자락.', 5, 1, 'published', NOW()),
  (24, '申', 'sin-branch', '신', '원숭이 신', 'earthly_branches', 'metal', 'yang', '여러 획이 접히는 구조', '금속이 드러나기 시작하는 가을 문턱으로 외운다.', '지지 9. 신금. 이동, 시작되는 금기운.', 5, 1, 'published', NOW()),
  (25, '酉', 'yu', '유', '닭 유', 'earthly_branches', 'metal', 'yin', '닫힌 그릇 형태', '정제되어 응축된 금속/가을 수확으로 기억한다.', '지지 10. 유금. 정제, 결실, 수확.', 7, 1, 'published', NOW()),
  (26, '戌', 'sul', '술', '개 술', 'earthly_branches', 'earth', 'yang', '건조하게 정리되는 형태', '문을 닫고 정리하는 가을 끝 흙으로 기억한다.', '지지 11. 술토. 마감, 정리, 건조한 흙.', 6, 1, 'published', NOW()),
  (27, '亥', 'hae', '해', '돼지 해', 'earthly_branches', 'water', 'yin', '부드럽게 감싸는 구조', '겨울로 넘어가는 깊은 물문으로 기억한다.', '지지 12. 해수. 저장, 큰 물의 시작.', 6, 1, 'published', NOW());

INSERT INTO `hanja_group_links` (`id`, `hanja_char_id`, `hanja_group_id`, `sort_order`)
VALUES
  (1, 1, 1, 1), (2, 2, 1, 2), (3, 3, 1, 3), (4, 4, 1, 4), (5, 5, 1, 5),

  (6, 6, 2, 1), (7, 7, 2, 2), (8, 8, 2, 3), (9, 9, 2, 4), (10, 10, 2, 5),
  (11, 11, 2, 6), (12, 12, 2, 7), (13, 13, 2, 8), (14, 14, 2, 9), (15, 15, 2, 10),

  (16, 16, 3, 1), (17, 17, 3, 2), (18, 18, 3, 3), (19, 19, 3, 4), (20, 20, 3, 5),
  (21, 21, 3, 6), (22, 22, 3, 7), (23, 23, 3, 8), (24, 24, 3, 9), (25, 25, 3, 10),
  (26, 26, 3, 11), (27, 27, 3, 12),

  (28, 1, 4, 1), (29, 2, 4, 2), (30, 3, 4, 3), (31, 4, 4, 4), (32, 5, 4, 5),
  (33, 6, 4, 6), (34, 16, 5, 1), (35, 6, 5, 2), (36, 18, 5, 3), (37, 22, 5, 4);

INSERT INTO `lesson_hanja_links` (`id`, `lesson_id`, `hanja_char_id`, `relation_type`, `sort_order`)
VALUES
  (1, 3, 1, 'primary', 1), (2, 3, 2, 'primary', 2), (3, 3, 3, 'primary', 3), (4, 3, 4, 'primary', 4), (5, 3, 5, 'primary', 5),
  (6, 4, 6, 'primary', 1), (7, 4, 7, 'primary', 2), (8, 4, 8, 'primary', 3), (9, 4, 9, 'primary', 4), (10, 4, 10, 'primary', 5),
  (11, 4, 11, 'primary', 6), (12, 4, 12, 'primary', 7), (13, 4, 13, 'primary', 8), (14, 4, 14, 'primary', 9), (15, 4, 15, 'primary', 10),
  (16, 5, 16, 'primary', 1), (17, 5, 17, 'primary', 2), (18, 5, 18, 'primary', 3), (19, 5, 19, 'primary', 4), (20, 5, 20, 'primary', 5),
  (21, 5, 21, 'primary', 6), (22, 5, 22, 'primary', 7), (23, 5, 23, 'primary', 8), (24, 5, 24, 'primary', 9), (25, 5, 25, 'primary', 10),
  (26, 5, 26, 'primary', 11), (27, 5, 27, 'primary', 12),
  (28, 6, 6, 'example', 1), (29, 6, 16, 'example', 2), (30, 6, 8, 'example', 3), (31, 6, 18, 'example', 4);

INSERT INTO `quiz_sets` (`id`, `lesson_id`, `code`, `title`, `scope_type`, `description`, `difficulty_level`, `pass_score`, `publish_status`, `published_at`)
VALUES
  (1, 3, 'QUIZ_FIVE_001', '오행 5글자 기본 퀴즈', 'lesson', '오행 한자의 뜻과 오행 분류를 맞히는 기본 퀴즈', 1, 70, 'published', NOW()),
  (2, 4, 'QUIZ_STEM_001', '천간 10글자 구별 퀴즈', 'lesson', '천간의 오행과 음양을 연결해보는 퀴즈', 2, 70, 'published', NOW()),
  (3, 5, 'QUIZ_BRANCH_001', '지지 12글자 기본 퀴즈', 'lesson', '지지를 글자/동물/오행으로 연결하는 퀴즈', 2, 70, 'published', NOW());

INSERT INTO `quiz_items` (`id`, `quiz_set_id`, `question_type`, `prompt_text`, `target_hanja_char_id`, `choices_json`, `answer_payload_json`, `explanation_text`, `hint_text`, `sort_order`, `points`)
VALUES
  (1, 1, 'multiple_choice', '木은 어떤 뜻인가요?', 1, JSON_ARRAY('불', '흙', '나무', '물'), JSON_OBJECT('correct_choice_index', 2), '木은 나무를 뜻하며 오행의 목에 해당합니다.', '줄기와 가지 이미지를 떠올려보세요.', 1, 10),
  (2, 1, 'multiple_choice', '火의 오행은 무엇인가요?', 2, JSON_ARRAY('wood', 'fire', 'earth', 'water'), JSON_OBJECT('correct_choice_index', 1), '火는 오행 중 화입니다.', '불꽃 이미지를 떠올려보세요.', 2, 10),
  (3, 1, 'multiple_choice', '金의 뜻으로 가장 가까운 것은?', 4, JSON_ARRAY('쇠', '물', '나무', '흙'), JSON_OBJECT('correct_choice_index', 0), '金은 쇠, 금속의 의미를 가집니다.', '가을과 결단 이미지를 같이 붙이면 기억이 쉽습니다.', 3, 10),
  (4, 1, 'multiple_choice', '물에 해당하는 한자를 고르세요.', NULL, JSON_ARRAY('木', '火', '水', '土'), JSON_OBJECT('correct_choice_index', 2), '水가 물입니다.', '흐르는 이미지!', 4, 10),

  (5, 2, 'multiple_choice', '甲의 오행은 무엇인가요?', 6, JSON_ARRAY('wood', 'fire', 'earth', 'metal'), JSON_OBJECT('correct_choice_index', 0), '甲은 양목입니다.', '갑은 시작하는 나무입니다.', 1, 10),
  (6, 2, 'multiple_choice', '丁은 어떤 성향에 더 가까운가요?', 9, JSON_ARRAY('양화', '음화', '양수', '음금'), JSON_OBJECT('correct_choice_index', 1), '丁은 음화입니다.', '촛불처럼 작고 선명한 불.', 2, 10),
  (7, 2, 'multiple_choice', '壬과 癸 중 큰 물에 더 가까운 글자는?', NULL, JSON_ARRAY('壬', '癸'), JSON_OBJECT('correct_choice_index', 0), '壬은 큰 물, 癸는 비나 이슬 같은 세밀한 물로 봅니다.', '바다 vs 이슬.', 3, 10),
  (8, 2, 'true_false', '庚은 금(金)에 속한다.', 12, NULL, JSON_OBJECT('correct_boolean', TRUE), '庚은 양금입니다.', '날이 선 금속 공구를 떠올려보세요.', 4, 10),

  (9, 3, 'multiple_choice', '子는 어떤 지지인가요?', 16, JSON_ARRAY('자', '묘', '술', '해'), JSON_OBJECT('correct_choice_index', 0), '子는 지지의 첫 글자 자입니다.', '자축인묘의 맨 앞!', 1, 10),
  (10, 3, 'multiple_choice', '午의 오행은 무엇인가요?', 22, JSON_ARRAY('wood', 'fire', 'earth', 'water'), JSON_OBJECT('correct_choice_index', 1), '午는 화 기운을 대표하는 지지입니다.', '정오의 태양을 떠올리면 쉽습니다.', 2, 10),
  (11, 3, 'multiple_choice', '가을의 금 기운과 가장 잘 연결되는 지지는?', NULL, JSON_ARRAY('寅', '酉', '亥', '卯'), JSON_OBJECT('correct_choice_index', 1), '酉는 가을의 금 기운과 연결됩니다.', '수확과 정제의 이미지.', 3, 10),
  (12, 3, 'multiple_choice', '겨울의 깊은 물문 같은 지지는?', 27, JSON_ARRAY('辰', '未', '亥', '巳'), JSON_OBJECT('correct_choice_index', 2), '亥는 겨울로 넘어가는 큰 물의 문처럼 이해할 수 있습니다.', '해수(亥水) 기억!', 4, 10);

INSERT INTO `lesson_attempts` (`id`, `user_id`, `lesson_id`, `status`, `progress_percent`, `latest_score`, `best_score`, `total_time_seconds`, `first_started_at`, `last_accessed_at`, `completed_at`)
VALUES
  (1, 2, 1, 'completed', 100.00, 95.00, 95.00, 780, NOW(), NOW(), NOW()),
  (2, 2, 3, 'in_progress', 45.00, 70.00, 70.00, 420, NOW(), NOW(), NULL);

INSERT INTO `review_cards` (`id`, `user_id`, `hanja_char_id`, `source_type`, `source_id`, `stage`, `ease_factor`, `interval_days`, `repetitions`, `due_at`, `last_result`, `last_reviewed_at`)
VALUES
  (1, 2, 1, 'lesson', 3, 'learning', 2.40, 1, 1, DATE_ADD(NOW(), INTERVAL 1 DAY), 'good', NOW()),
  (2, 2, 6, 'lesson', 4, 'new', 2.50, 0, 0, NOW(), NULL, NULL),
  (3, 2, 16, 'lesson', 5, 'new', 2.50, 0, 0, NOW(), NULL, NULL);

INSERT INTO `review_logs` (`id`, `review_card_id`, `user_id`, `reviewed_at`, `result`, `response_ms`, `score`, `before_state_json`, `after_state_json`)
VALUES
  (1, 1, 2, NOW(), 'good', 3200, 90.00, JSON_OBJECT('stage', 'new', 'interval_days', 0), JSON_OBJECT('stage', 'learning', 'interval_days', 1));

INSERT INTO `bookmarks` (`id`, `user_id`, `target_type`, `target_id`, `note`)
VALUES
  (1, 2, 'lesson', 3, '오행은 자주 다시 보려고 북마크'),
  (2, 2, 'hanja_char', 6, '甲이 자꾸 헷갈려서 체크');

INSERT INTO `saju_examples` (`id`, `code`, `slug`, `title`, `description`, `gender`, `solar_birth_datetime`, `lunar_birth_label`, `year_stem`, `year_branch`, `month_stem`, `month_branch`, `day_stem`, `day_branch`, `hour_stem`, `hour_branch`, `chart_json`, `difficulty_level`, `publish_status`, `published_at`)
VALUES
  (1, 'EXAMPLE_CHART_001', 'spring-wood-fire-sample', '샘플 A | 목화 흐름 읽기', '천간과 지지를 처음 읽는 학습자를 위한 샘플 차트. 목과 화가 보이는 구조를 중심으로 본다.', 'unknown', NULL, NULL, '甲', '子', '丙', '寅', '乙', '巳', '庚', '午', JSON_OBJECT('focus', JSON_ARRAY('연주/월주/일주/시주 위치 읽기', '오행 색상 인식', '천간과 지지 분리 보기')), 1, 'published', NOW()),
  (2, 'EXAMPLE_CHART_002', 'metal-water-sample', '샘플 B | 금수 흐름 읽기', '가을/겨울 감각이 들어간 금수 중심 예시 차트.', 'unknown', NULL, NULL, '辛', '酉', '癸', '亥', '壬', '辰', '丁', '未', JSON_OBJECT('focus', JSON_ARRAY('금과 수의 연결', '지지 위치 읽기', '차트 비교 관찰')), 2, 'published', NOW());

INSERT INTO `admin_audit_logs` (`id`, `admin_user_id`, `entity_type`, `entity_id`, `action_type`, `diff_json`, `ip_address`, `user_agent`)
VALUES
  (1, 1, 'seed', NULL, 'initial_seed', JSON_OBJECT('version', 'mvp_mysql8_v1', 'notes', '기초 트랙/한자/퀴즈/샘플 차트 seed 입력'), '127.0.0.1', 'seed-script');

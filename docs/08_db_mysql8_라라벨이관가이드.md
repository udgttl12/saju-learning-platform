# Laravel migration 이관 가이드

## 추천 migration 순서

아래 순서로 쪼개면 FK 충돌이 적습니다.

1. `create_users_table`
2. `create_profiles_table`
3. `create_learning_tracks_table`
4. `create_track_enrollments_table`
5. `create_lessons_table`
6. `create_lesson_steps_table`
7. `create_hanja_groups_table`
8. `create_hanja_chars_table`
9. `create_hanja_group_links_table`
10. `create_lesson_hanja_links_table`
11. `create_stroke_templates_table`
12. `create_practice_sessions_table`
13. `create_practice_strokes_table`
14. `create_quiz_sets_table`
15. `create_quiz_items_table`
16. `create_lesson_attempts_table`
17. `create_review_cards_table`
18. `create_review_logs_table`
19. `create_bookmarks_table`
20. `create_saju_examples_table`
21. `create_admin_audit_logs_table`

## Laravel 구현 팁

### 1) status/role은 enum보다 string 추천
초기 서비스는 상태값이 자주 바뀝니다.  
Laravel + MySQL 환경에서는 enum 변경이 귀찮아지는 순간이 자주 옵니다.

추천:
- DB: `string`
- 앱 코드: PHP Enum 또는 상수 클래스

### 2) SoftDeletes 권장 테이블
- users
- learning_tracks
- lessons
- hanja_chars
- saju_examples

### 3) JSON cast 추천 컬럼
- lessons.unlock_rule_json
- lesson_steps.payload_json
- stroke_templates.svg_path_json
- stroke_templates.guide_meta_json
- practice_sessions.session_meta_json
- practice_strokes.points_json
- practice_strokes.bbox_json
- quiz_items.choices_json
- quiz_items.answer_payload_json
- review_logs.before_state_json
- review_logs.after_state_json
- saju_examples.chart_json
- admin_audit_logs.diff_json

### 4) 모델 관계 예시
- User hasOne Profile
- User hasMany TrackEnrollment
- LearningTrack hasMany Lesson
- Lesson hasMany LessonStep
- Lesson belongsToMany HanjaChar via lesson_hanja_links
- HanjaChar belongsToMany HanjaGroup via hanja_group_links
- HanjaChar hasMany StrokeTemplate
- User hasMany ReviewCard

### 5) 운영 화면 우선순위
1차 관리자 CRUD는 아래만 먼저 만들어도 충분합니다.

- HanjaChar
- LearningTrack
- Lesson
- LessonStep
- QuizSet
- QuizItem
- SajuExample

## seed 분리 추천

Laravel로 옮길 때는 아래처럼 분리하면 관리가 편합니다.

- `UserSeeder`
- `LearningTrackSeeder`
- `LessonSeeder`
- `HanjaGroupSeeder`
- `HanjaCharSeeder`
- `QuizSeeder`
- `SajuExampleSeeder`

## 주의 포인트

### 1) `slug`는 앱 레벨에서 생성
DB에서 자동 생성보다 Laravel service로 통제하는 것이 낫습니다.

### 2) `bookmarks.target_type + target_id`는 polymorphic처럼 운용
1차는 generic relation으로 충분합니다.

### 3) `stroke_templates`는 후순위
따라쓰기 UX가 이 서비스의 꽃이긴 하지만, 정확한 벡터 데이터가 없으면 오히려 품질이 흔들립니다.
MVP에서는 이미지/GIF 가이드로 먼저 열고, 이후 SVG path로 넘어가는 방식이 안전합니다.

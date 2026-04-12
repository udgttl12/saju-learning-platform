# Laravel Migration Pack | 사주 입문 플랫폼

이 패키지는 `05_db_mysql8_schema.sql`을 Laravel migration 파일 세트로 분해한 결과물입니다.

## 포함 범위
- users
- profiles
- learning_tracks
- track_enrollments
- lessons
- lesson_steps
- hanja_groups
- hanja_chars
- hanja_group_links
- lesson_hanja_links
- stroke_templates
- practice_sessions
- practice_strokes
- quiz_sets
- quiz_items
- lesson_attempts
- review_cards
- review_logs
- bookmarks
- saju_examples
- admin_audit_logs

## 사용 방법
1. 파일을 Laravel 프로젝트의 `database/migrations` 아래에 복사합니다.
2. `.env`의 DB 연결을 MySQL 8로 맞춥니다.
3. 아래 명령을 실행합니다.

```bash
php artisan migrate
```

## 주의 사항
- 이 migration 세트는 MySQL 8 기준입니다.
- 기존 seed SQL은 별도이며, 아직 Seeder PHP 파일로 완전히 분해하지 않았습니다.
- `stroke_templates.svg_path_json`은 구조만 준비되어 있고 실제 획순 벡터 데이터는 비어 있어도 됩니다.
- `slug`와 `email`은 단일 unique로 두었습니다. soft delete 후에도 같은 slug/email 재사용 정책을 허용하려면 별도 정책 조정이 필요합니다.

## 다음 추천 작업
- Seeder PHP 변환
- Eloquent Model 생성
- enum성 문자열을 PHP Enum 또는 상수 클래스로 정리
- review_cards 스케줄링 로직 서비스 구현

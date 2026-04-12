# MySQL 8 DB 패키지 안내

이 폴더는 기존 내부 개발용 문서 패키지에 **실제 실행 가능한 MySQL 8 스키마와 MVP seed**를 추가한 버전입니다.

## 포함 파일

- `05_db_mysql8_schema.sql`
  - MySQL 8 기준 스키마
  - Laravel 모노리스 구조를 염두에 둔 테이블 / FK / 인덱스 포함
- `06_db_mysql8_seed_mvp.sql`
  - 관리자 계정 1개
  - 데모 회원 1개
  - 학습 트랙 5개
  - 레슨 6개
  - 오행 5 + 천간 10 + 지지 12 = 핵심 한자 27개
  - 퀴즈 세트 3개 / 퀴즈 문항 12개
  - 샘플 만세력 2개
- `07_db_mysql8_라라벨이관가이드.md`
  - SQL 스키마를 Laravel migration으로 옮길 때의 순서와 주의점

## 이 버전에서 추가한 테이블

기존 워크북의 초안에서 실무상 바로 필요한 테이블 3개를 추가했습니다.

1. `hanja_groups`
   - `hanja_group_links`를 실제로 받쳐주는 그룹 마스터
   - 오행 / 천간 / 지지 / 입문핵심 컬렉션 구분용

2. `lesson_hanja_links`
   - 레슨과 한자를 직접 연결하는 pivot
   - 특정 레슨에 어떤 글자가 노출되는지 조회하기 쉬워집니다.

3. `track_enrollments`
   - 사용자 단위 트랙 시작/완료/진행률 저장용
   - 레슨 진행률만으로도 계산은 가능하지만, 홈 대시보드와 재진입 UX에서 이 테이블이 훨씬 편합니다.

## 의도적으로 비워둔 영역

이 패키지는 “DB 뼈대 + MVP 시작 데이터”까지입니다. 아래는 일부러 비워두었습니다.

- `stroke_templates.svg_path_json`
  - 실제 따라쓰기 품질은 벡터 원본 정제 수준에 크게 좌우됩니다.
  - 이 값은 신뢰 가능한 획순 데이터 소스를 붙인 뒤 채우는 것이 안전합니다.

- 대량 퀴즈 은행
  - 지금은 구조 검증용 문항 12개만 넣었습니다.
  - 실제 운영 전에는 최소 100문항 이상으로 늘리는 쪽이 좋습니다.

- AI 해석 관련 로그/프롬프트 저장
  - 현재 범위는 “사주 입문 플랫폼”이기 때문에 1차 DB에서는 제외했습니다.
  - 나중에 `ai_requests`, `ai_interpretations` 같은 테이블을 별도 추가하는 편이 깔끔합니다.

## seed 계정

- 관리자: `admin@example.com`
- 데모회원: `demo@example.com`
- 비밀번호: 둘 다 `password`

## 추천 실행 순서

```sql
SOURCE 05_db_mysql8_schema.sql;
SOURCE 06_db_mysql8_seed_mvp.sql;
```

## 다음 단계 추천

1. Laravel migration 파일로 분해
2. Eloquent Model 생성
3. Admin CRUD
   - learning_tracks
   - lessons
   - lesson_steps
   - hanja_chars
   - quiz_sets / quiz_items
4. stroke_templates 수급/정제
5. 복습 로직 서비스화

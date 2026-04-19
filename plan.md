# 사주 입문 플랫폼 구현 계획서

> **문서 버전**: v1.0 | **작성일**: 2026-04-12  
> **기준 문서**: PRD v0.9, 서비스기획서, 기능명세서, IA/백로그, DB 스키마, 라라벨 이관 가이드

---

## 1. 프로젝트 개요

### 1.1 한 줄 정의
"한자를 몰라도 시작할 수 있는 사주 입문 학교" — 성인 취미 학습자 대상 사주 한자 학습 웹 플랫폼

### 1.2 핵심 가치
- **읽기**: 오행·천간·지지 한자를 보고 뜻과 역할을 인식
- **쓰기**: 직접 따라쓰며 기억 고정
- **연결하기**: 배운 글자를 샘플 만세력에서 재확인

### 1.3 MVP 범위 요약
| 포함 | 제외 (2차) |
|------|-----------|
| 온보딩, 학습 트랙/레슨, 한자 카드 | 개인 만세력 생년월일시 입력 |
| 쓰기 연습 (캔버스), 퀴즈/복습 | 정교한 필적 자동 채점 |
| 한자 사전, 샘플 만세력 실전관 | AI 튜터 |
| 마이페이지, 관리자 CMS | 유료 결제, 커뮤니티 |
| 비회원 체험 + 회원 진행 저장 | 심화 명리 (격국/용신/신살) |

---

## 2. 기술 스택

| 영역 | 선택 | 비고 |
|------|------|------|
| **웹 프레임워크** | Laravel 13 | Laravel Breeze 기반 인증 + 세션 |
| **동적 UI** | Blade + Alpine.js | 서버 렌더링 + form 라운드트립 + Alpine 클라이언트 상태. Livewire는 MVP 범위 밖(Phase 2 재검토). |
| **데이터베이스** | MySQL 8 | utf8mb4, 스키마/시드 SQL 준비 완료 |
| **쓰기 캔버스** | HTML Canvas + Pointer Events | mouse/pen/touch 통합 |
| **파일 저장** | S3 호환 스토리지 | 획순 SVG, 가이드 이미지 |
| **PHP 버전** | PHP 8.4+ | Laravel 13 요구사항 |
| **패키지 관리** | Composer (PHP), npm (JS) | |
| **Python** | Miniforge (conda) | 보조 스크립트/도구용 |
| **개발 환경** | Windows 11 + WSL2 또는 Laragon | |

---

## 3. 정보 구조 (IA)

```
/ ............................ 홈 (오늘의 한자, 진행률, 시작 CTA)
/start ....................... 온보딩 (3문항: 초보 여부, 한자 경험, 학습량)
/tracks/{slug} ............... 학습 트랙 목록
  /tracks/hanja-prep ......... 0단계: 한자 준비운동
  /tracks/five-elements ...... 1단계: 오행 5글자
  /tracks/heavenly-stems ..... 2단계: 천간 10글자
  /tracks/earthly-branches ... 3단계: 지지 12글자
  /tracks/chart-reading ...... 5단계: 만세력 첫 읽기
/lessons/{slug} .............. 레슨 플레이어 (step 기반)
/characters/{slug} ........... 한자 카드 상세
/practice/{slug} ............. 쓰기 연습 캔버스
/review ...................... 퀴즈/복습
/dictionary .................. 한자 사전
/lab/sample-chart ............ 실전관 (샘플 만세력)
/me .......................... 마이페이지

/admin ....................... 관리자 대시보드
/admin/characters ............ 한자 CMS
/admin/lessons ............... 레슨/스텝 CMS
/admin/quizzes ............... 퀴즈 CMS
/admin/examples .............. 샘플 차트 CMS
/admin/audit-logs ............ 운영 로그
```

---

## 4. 데이터베이스 설계

### 4.1 테이블 목록 (21개)

스키마와 시드 SQL은 `docs/05_db_mysql8_schema.sql`, `docs/06_db_mysql8_seed_mvp.sql`에 준비 완료.  
Laravel migration 파일은 `docs/sql/database/migrations/`에 준비 완료.  
Eloquent Model은 `docs/seeder/app/Models/`에 준비 완료.

| # | 테이블 | 역할 | SoftDeletes |
|---|--------|------|:-----------:|
| 1 | `users` | 회원 계정 | O |
| 2 | `profiles` | 회원 상세/온보딩 | |
| 3 | `learning_tracks` | 학습 트랙 | O |
| 4 | `track_enrollments` | 트랙 등록 상태 | |
| 5 | `lessons` | 레슨 | O |
| 6 | `lesson_steps` | 레슨 내부 스텝 | |
| 7 | `hanja_groups` | 한자 그룹 (오행/천간/지지) | |
| 8 | `hanja_chars` | 한자 카드 마스터 | O |
| 9 | `hanja_group_links` | 한자-그룹 연결 | |
| 10 | `lesson_hanja_links` | 레슨-한자 연결 | |
| 11 | `stroke_templates` | 획순 템플릿 | |
| 12 | `practice_sessions` | 쓰기 연습 세션 | |
| 13 | `practice_strokes` | 획 좌표 로그 | |
| 14 | `quiz_sets` | 퀴즈 세트 | |
| 15 | `quiz_items` | 퀴즈 문항 | |
| 16 | `lesson_attempts` | 레슨 진행 상태 | |
| 17 | `review_cards` | 복습 카드 (spaced repetition) | |
| 18 | `review_logs` | 복습 이력 | |
| 19 | `bookmarks` | 즐겨찾기 | |
| 20 | `saju_examples` | 샘플 만세력 | O |
| 21 | `admin_audit_logs` | 관리자 감사 로그 | |

### 4.2 핵심 관계

```
User ──1:1──> Profile
User ──1:N──> TrackEnrollment ──N:1──> LearningTrack
LearningTrack ──1:N──> Lesson ──1:N──> LessonStep
Lesson ──M:N──> HanjaChar (via lesson_hanja_links)
HanjaChar ──M:N──> HanjaGroup (via hanja_group_links)
HanjaChar ──1:N──> StrokeTemplate
User ──1:N──> PracticeSession ──1:N──> PracticeStroke
Lesson ──1:N──> QuizSet ──1:N──> QuizItem
User ──1:N──> LessonAttempt
User ──1:N──> ReviewCard ──1:N──> ReviewLog
User ──1:N──> Bookmark
```

### 4.3 시드 데이터 (MVP)

- 관리자 1명 (`admin@example.com` / `password`)
- 데모 회원 1명 (`demo@example.com` / `password`)
- 학습 트랙 5개 (준비운동, 오행, 천간, 지지, 만세력 읽기)
- 레슨 6개, 레슨 스텝 14개
- 핵심 한자 27개 (오행 5 + 천간 10 + 지지 12)
- 한자 그룹 5개, 퀴즈 3세트/12문항
- 샘플 만세력 2개

---

## 5. 사용자 역할 및 권한

| 역할 | 접근 범위 | 비고 |
|------|---------|------|
| **Guest** | 홈, 샘플 레슨, 샘플 만세력 열람 | 진행 저장 불가, 복습 제한 |
| **Member** | 진행률, 복습 카드, 즐겨찾기, 마이페이지 | 기본 사용자 |
| **Admin** | 전체 CMS, 운영 로그 | `users.role === 'admin'` 단일 판정 (`AdminMiddleware`) |
| **Editor** _(Phase 2 예정)_ | 한자/레슨/퀴즈 등록 및 수정 | 현재 미구현. 운영자 권한은 Admin으로 흡수. |

---

## 6. 핵심 비즈니스 로직

### 6.1 학습 진행
1. 온보딩 3문항 → 추천 트랙 배정
2. 트랙 내 레슨 순차 진행 (선행 트랙 완료 후 다음 해금)
3. 레슨 = step 기반 (intro → explanation → stroke_order → guided_practice → quiz → summary)
4. 필수 step 100% 완료 시 레슨 완료 처리
5. 트랙 내 모든 레슨 완료 시 트랙 완료

### 6.2 쓰기 연습
1. Pointer Events로 좌표 경로 캡처 (mouse/pen/touch)
2. 모드: trace(따라쓰기) → overlay(겹쳐보기) → free(자유쓰기)
3. undo/clear 지원
4. 자기평가 (쉬움/보통/어려움) → 복습 우선순위 반영
5. 회원만 영구 저장

### 6.3 퀴즈
- 문항 유형 4종: multiple_choice, true_false, short_answer, self_check
- 레슨 종료 퀴즈 3~5문, 복습 퀴즈 5~10문
- 정답률 80% 미만 OR 자기평가 "어려움" → 복습 카드 생성

### 6.4 복습 (Spaced Repetition)
- 스테이지: new → learning → reviewing → lapsed → mastered
- 초기 간격: 당일, +1일, +3일, +7일, +14일
- SM-2 알고리즘 기반 ease_factor 조정
- 결과: again(다시) / hard(어려움) / good(좋음) / easy(쉬움)
- 같은 글자 사용자당 1장 유지 (중복 제거)

### 6.5 실전관 (샘플 만세력)
- 샘플 차트의 8글자를 연주/월주/일주/시주로 구분 표시
- 오행 색상 시각화 (목:청, 화:적, 토:황, 금:백, 수:흑)
- 각 글자 클릭 → 해당 한자 카드로 이동

---

## 7. Sprint 계획

### Sprint 0: 프로젝트 셋업 (1주)

**목표**: 개발 환경 구축 + 프로젝트 골격 완성

| # | 작업 | 상세 |
|---|------|------|
| 0-1 | 개발 환경 구성 | PHP 8.3+, Composer, Node.js, MySQL 8, Miniforge (Python) |
| 0-2 | Laravel 13 프로젝트 생성 | `laravel new` + Laravel Breeze (Blade stack) |
| 0-3 | Alpine.js + Tailwind 설정 | Breeze 기본 포함. Livewire는 MVP에서 미사용. |
| 0-4 | DB 마이그레이션 적용 | `docs/sql/database/migrations/` → `database/migrations/` 복사 후 `php artisan migrate` |
| 0-5 | Eloquent Model 적용 | `docs/seeder/app/Models/` → `app/Models/` 복사 |
| 0-6 | Factory 적용 | `docs/seeder/database/factories/` → `database/factories/` 복사 |
| 0-7 | Seeder 작성 | `docs/06_db_mysql8_seed_mvp.sql` 기반 Laravel Seeder PHP 변환 |
| 0-8 | 인증 골격 | Laravel Breeze/Fortify 기반 이메일 가입/로그인 |
| 0-9 | 공통 레이아웃 | Blade 레이아웃, Tailwind CSS, 모바일 반응형 기본 |
| 0-10 | 라우트 골격 | 사용자 영역 + 관리자 영역 라우트 등록 |
| 0-11 | 권한/미들웨어 | Guest/Member/Admin 역할 미들웨어 (Editor는 Phase 2) |
| 0-12 | Git 설정 | .gitignore, 브랜치 전략, CLAUDE.md |

**산출물**: 프로젝트 기동 가능, DB 스키마/시드 적용 완료, 인증 동작

---

### Sprint 1: 온보딩 + 학습 코어 (2주)

**목표**: 온보딩부터 레슨 플레이어까지 학습 핵심 흐름 완성

| # | 작업 | 상세 |
|---|------|------|
| 1-1 | 홈 화면 | 오늘의 한자, 진행률 요약, 시작 CTA |
| 1-2 | 온보딩 플로우 | 3문항 (초보여부, 한자경험, 학습량) → 추천 트랙 배정 |
| 1-3 | 학습 트랙 목록 | 트랙 카드, 진행률, 해금 상태 표시 |
| 1-4 | 레슨 플레이어 | step 기반 진행 (intro, explanation, summary 타입) |
| 1-5 | 진행률 저장 | lesson_attempts, track_enrollments 업데이트 로직 |
| 1-6 | 학습 대시보드 | 오늘 할 일, 이어서 학습, 복습 대기 수 |
| 1-7 | 비회원 체험 | 세션 기반 임시 저장 → 가입 시 이관 |

**산출물**: 가입 → 온보딩 → 트랙 선택 → 레슨 진행 → 완료까지 풀 플로우

---

### Sprint 2: 한자 카드 + 쓰기 캔버스 (2주)

**목표**: 한자 학습과 쓰기 경험의 핵심 기능 완성

| # | 작업 | 상세 |
|---|------|------|
| 2-1 | 한자 카드 상세 | 큰 글자, 읽기, 뜻, 오행/음양, 기억법, 사주 역할 |
| 2-2 | 한자 카드 그리드 | 오행/천간/지지 카드 그리드 뷰 |
| 2-3 | 레슨 내 카드 통합 | hanja_card 타입 레슨 스텝에서 카드 표시 |
| 2-4 | 획순 보기 | SVG/이미지 기반 획순 가이드 (MVP: 이미지/GIF 우선) |
| 2-5 | 쓰기 캔버스 구현 | Canvas + Pointer Events, 3모드 (trace/overlay/free) |
| 2-6 | 쓰기 UX | undo, clear, 재생, 가이드선 오버레이 |
| 2-7 | 자기평가 | 쓰기 후 쉬움/보통/어려움 선택 |
| 2-8 | 쓰기 데이터 저장 | practice_sessions, practice_strokes 저장 |
| 2-9 | 레슨 내 쓰기 통합 | stroke_order, guided_practice 타입 스텝 |

**산출물**: 한자 카드 보기 + 쓰기 연습 캔버스 동작

---

### Sprint 3: 퀴즈 + 복습 + 관리자 CMS (2주)

**목표**: 퀴즈/복습 루프와 운영 도구 완성

| # | 작업 | 상세 |
|---|------|------|
| 3-1 | 퀴즈 엔진 | 4종 문항 렌더링 + 채점 로직 |
| 3-2 | 레슨 내 퀴즈 | quiz 타입 스텝 통합 |
| 3-3 | 복습 카드 생성 | 퀴즈 결과 + 자기평가 기반 자동 생성 |
| 3-4 | 복습 큐 화면 | 오늘 복습 목록, 카드 플레이어 |
| 3-5 | 복습 로직 | SM-2 기반 due_at 계산, 스테이지 전이 |
| 3-6 | 한자 사전 | 검색 (full-text), 필터 (오행/천간/지지), 목록 |
| 3-7 | 즐겨찾기 | 한자 카드/레슨 북마크 |
| 3-8 | 관리자: 한자 CMS | HanjaChar CRUD, 게시 상태 관리 |
| 3-9 | 관리자: 레슨 CMS | LearningTrack, Lesson, LessonStep CRUD |
| 3-10 | 관리자: 퀴즈 CMS | QuizSet, QuizItem CRUD |

**산출물**: 퀴즈 풀기 → 복습 카드 생성 → 복습 순환 동작, 관리자 콘텐츠 관리 가능

---

### Sprint 4: 실전관 + 마이페이지 + 마감 (1.5주)

**목표**: 실전 연결과 사용자 경험 마감

| # | 작업 | 상세 |
|---|------|------|
| 4-1 | 샘플 만세력 화면 | 8글자 표, 연/월/일/시주 구분, 오행 색상 |
| 4-2 | 글자-카드 연결 | 차트 글자 클릭 → 한자 카드 이동 |
| 4-3 | 관리자: 샘플 차트 CMS | SajuExample CRUD |
| 4-4 | 마이페이지 | 진행률, 최근 학습, 즐겨찾기, 복습 대기 수 |
| 4-5 | 연속 학습일 | 하루 1개 이상 step 완료 기준 카운트 |
| 4-6 | 관리자: 운영 로그 | admin_audit_logs 조회 화면 |

**산출물**: 학습 → 실전관 연결, 마이페이지 완성

---

### Sprint 5: QA + 배포 준비 (1주)

**목표**: 품질 검증 및 배포

| # | 작업 | 상세 |
|---|------|------|
| 5-1 | 기능 테스트 | 핵심 플로우 E2E 테스트 |
| 5-2 | 반응형 검증 | 모바일 360px ~ 데스크톱 |
| 5-3 | 성능 최적화 | LCP 2.5초 이내, 쿼리 최적화 |
| 5-4 | 접근성 검증 | 키보드 포커스, 대비, 터치 영역 |
| 5-5 | 콘텐츠 검수 | 한자/퀴즈 정확성, 설명 길이 |
| 5-6 | 배포 구성 | 서버 환경, .env, 캐시, 큐 |
| 5-7 | 운영 체크리스트 | 모니터링, 백업, 에러 로깅 |

**산출물**: MVP 배포 완료

---

## 8. 파일 구조 (예상)

> 현재 레포 기준 실제 구조. 관리자 라우트는 별도 파일 없이 `routes/web.php` 하나에서 `->prefix('admin')->middleware(['auth','admin'])` 그룹으로 구성된다.

```
saju-learning-platform/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── OnboardingController.php
│   │   │   ├── TrackController.php
│   │   │   ├── LessonController.php
│   │   │   ├── HanjaCharController.php
│   │   │   ├── QuizController.php
│   │   │   ├── ReviewController.php
│   │   │   ├── DictionaryController.php
│   │   │   ├── LabController.php
│   │   │   ├── ExamController.php
│   │   │   ├── BookmarkController.php
│   │   │   ├── ProfileController.php
│   │   │   └── Admin/
│   │   │       ├── AdminDashboardController.php
│   │   │       ├── HanjaCharController.php
│   │   │       ├── LearningTrackController.php
│   │   │       ├── LessonController.php
│   │   │       ├── QuizSetController.php
│   │   │       ├── SajuExampleController.php
│   │   │       └── AuditLogController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── EnsureOnboardingCompleted.php
│   ├── Models/           # 21개 Eloquent 모델 (User, Profile, LearningTrack, Lesson, LessonStep, HanjaChar, ... , AdminAuditLog)
│   └── Services/
│       ├── QuizService.php
│       └── ReviewService.php
├── bootstrap/
│   └── app.php           # 미들웨어 alias(admin, onboarding) 등록
├── database/
│   ├── migrations/       # 22개 마이그레이션
│   ├── factories/
│   └── seeders/          # DatabaseSeeder가 콘텐츠 테이블 truncate 후 재시드
├── resources/
│   ├── views/
│   │   ├── layouts/      # app / admin / guest / navigation
│   │   ├── components/   # Breeze 기본 Blade 컴포넌트
│   │   ├── <도메인>/      # tracks, lessons, hanja, quiz, review, lab, dictionary, exam, bookmarks, profile, admin
│   │   └── dashboard.blade.php
│   ├── css/app.css       # Tailwind (darkMode: class)
│   └── js/
│       ├── app.js        # Alpine.js + axios 부트스트랩
│       └── bootstrap.js
├── routes/
│   ├── web.php           # 사용자 + 관리자 라우트 (단일 파일)
│   ├── auth.php          # Breeze 인증
│   └── console.php
├── docs/                 # 기획 문서 패키지 + 초기 이관 자산(sql/, seeder/)
├── tests/                # Feature / Unit (phpunit, in-memory sqlite)
├── plan.md
└── CLAUDE.md
```

---

## 9. 환경 설정 가이드

### 9.1 필수 도구 설치

```bash
# PHP 8.4+ & Composer
# Node.js 20+ & npm
# MySQL 8

# Python (Miniforge 사용)
# https://github.com/conda-forge/miniforge 에서 Miniforge3 설치
# 설치 후:
conda create -n saju python=3.12
conda activate saju
```

### 9.2 프로젝트 초기화

```bash
# Laravel 프로젝트 생성
composer create-project laravel/laravel . "^13.0"

# Laravel Breeze (Blade stack) 설치
composer require laravel/breeze --dev
php artisan breeze:install blade

# 프론트엔드 빌드
npm install
npm run dev

# DB 설정 (.env)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saju_intro_platform
DB_USERNAME=root
DB_PASSWORD=

# 마이그레이션 + 시드
php artisan migrate
php artisan db:seed
```

---

## 10. 이벤트 로그 설계

학습 분석과 KPI 측정을 위한 핵심 이벤트:

| 이벤트 | Payload | 용도 |
|--------|---------|------|
| `lesson_started` | user_id, lesson_id, track_id | 레슨 진입률 |
| `step_completed` | user_id, lesson_id, step_id, step_type | 이탈 step 파악 |
| `practice_completed` | user_id, hanja_id, mode, self_rating | 쓰기 참여율 |
| `quiz_submitted` | user_id, lesson_id, score, wrong_ids | 정답률, 약점 |
| `review_completed` | user_id, card_id, result, stage | 복습 성과 |
| `dictionary_search` | user_id, query, filter | 탐색 수요 |
| `sample_chart_opened` | user_id, example_id | 실전관 전환율 |
| `bookmark_toggled` | user_id, target_type, target_id | 관심 글자 |

---

## 11. 성공 지표 (KPI)

| 영역 | 지표 | MVP 목표 |
|------|------|---------|
| 활성화 | 온보딩 완료율 | 65%+ |
| 진입 | 첫 레슨 시작률 | 55%+ |
| 지속 | 첫 주 3회+ 재방문율 | 30%+ |
| 참여 | 레슨 내 쓰기 진입률 | 70%+ |
| 이해 | 퀴즈 정답률 평균 | 75%+ |
| 연결 | 샘플 만세력 진입률 | 25%+ |
| 학습 | 오행 27자 정답률 | 80%+ |
| 습관 | 7일 재방문율 | 25%+ |

---

## 12. 리스크 및 대응

| 리스크 | 대응 전략 |
|--------|---------|
| 획순 SVG 데이터 확보 어려움 | MVP는 이미지/GIF 가이드 우선, 이후 SVG path 전환 |
| 쓰기 UX 마우스 입력 품질 | 따라쓰기/오버레이/undo/clear 기본 제공 |
| 콘텐츠 과밀 → 이탈 | 한 레슨 5~8분, 카드당 설명 3문장 이내 |
| 천간/지지 학습 구간 이탈 | 복습 알림, 성취 시각화, 단계 잠금 |
| 운영 병목 (개발 의존) | 관리자 CMS로 콘텐츠 무중단 수정 |
| 사주 전문용어 장벽 | 쉬운 표현 + 용어 사전 + 단계별 노출 |

---

## 13. 2차 확장 범위 (Phase 2)

MVP 이후 순차적으로 확장할 기능:

1. **개인 만세력**: 생년월일시 입력 → 개인 차트 생성
2. **기존 엔진 브리지**: Dart CLI 만세력 엔진 → Laravel 연동
3. **AI 튜터**: 학습 질문형 제한 도입 (Claude API)
4. **심화 학습**: 간지 조합 읽기 (4단계), 십신/12운성 기초
5. **리마인드**: 복습 알림 이메일/푸시
6. **획순 고도화**: SVG path 기반 따라쓰기 + 유사도 비교
7. **유료 플랜**: 심화 콘텐츠 접근 제어

---

## 14. 준비 완료된 자산

docs 폴더에 이미 준비된 개발 자산 목록:

| 자산 | 경로 | 상태 |
|------|------|------|
| MySQL 8 스키마 SQL | `docs/05_db_mysql8_schema.sql` | 즉시 사용 가능 |
| MVP 시드 SQL | `docs/06_db_mysql8_seed_mvp.sql` | 즉시 사용 가능 |
| Laravel Migration (21개) | `docs/sql/database/migrations/` | 복사 후 사용 |
| Eloquent Model (21개) | `docs/seeder/app/Models/` | 복사 후 사용 |
| Factory (6개) | `docs/seeder/database/factories/` | 복사 후 사용 |
| DB 설계 안내 | `docs/07_db_mysql8_설계안내.md` | 참고 문서 |
| Laravel 이관 가이드 | `docs/08_db_mysql8_라라벨이관가이드.md` | 참고 문서 |
| 기존 만세력 엔진 명세 | `docs/04_참고명세_기존SajuApp_feature.md` | 2차 연동 참고 |

---

## 15. 구현 시작 순서 (체크리스트)

### Phase 0: 즉시 실행 (Sprint 0)

- [ ] PHP 8.4, Composer, Node.js, MySQL 8 설치 확인
- [ ] Miniforge로 Python 환경 구성
- [ ] Laravel 13 프로젝트 생성
- [ ] Laravel Breeze (Blade) + Tailwind CSS 설치
- [ ] `docs/sql/database/migrations/` → `database/migrations/` 복사
- [ ] `docs/seeder/app/Models/` → `app/Models/` 복사
- [ ] `docs/seeder/database/factories/` → `database/factories/` 복사
- [ ] `.env` DB 설정 → `php artisan migrate`
- [ ] Seeder PHP 작성 → `php artisan db:seed`
- [ ] Laravel Breeze 인증 설정
- [ ] 공통 Blade 레이아웃 + Tailwind 반응형
- [ ] 라우트 골격 (web.php, admin.php)
- [ ] 역할 미들웨어 (Guest/Member/Admin — Editor는 Phase 2)

### Phase 1: 핵심 학습 (Sprint 1-2)

- [ ] 홈 화면
- [ ] 온보딩 3문항
- [ ] 트랙 목록 + 레슨 플레이어
- [ ] 한자 카드 상세/그리드
- [ ] 쓰기 캔버스 (Canvas + Pointer Events)
- [ ] 진행률 저장

### Phase 2: 상호작용 (Sprint 3)

- [ ] 퀴즈 엔진 + 채점
- [ ] 복습 카드 + SM-2 스케줄러
- [ ] 한자 사전 + 검색
- [ ] 즐겨찾기
- [ ] 관리자 CMS (한자/레슨/퀴즈)

### Phase 3: 마감 (Sprint 4-5)

- [ ] 샘플 만세력 실전관
- [ ] 마이페이지
- [ ] 관리자 운영 로그
- [ ] QA + 성능 최적화
- [ ] 배포

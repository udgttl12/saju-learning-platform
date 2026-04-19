# AGENTS.md

This file provides guidance to Codex (Codex.ai/code) when working with code in this repository.

## Project

"사주 입문 플랫폼" — 성인 취미 학습자를 위한 사주 한자 학습 웹앱. Laravel 13 + Breeze(Blade) + MySQL 8 모노리스. 상세 요구사항·Sprint 계획·DB 설계는 `plan.md`와 `docs/`.

## Stack

공식 스택은 **Laravel 13 + Laravel Breeze (Blade stack) + Alpine.js + Tailwind v3**. 동적 UI는 컨트롤러 + 서버 렌더링 + form 라운드트립 + Alpine 클라이언트 상태로 구성하며, **Livewire는 도입하지 않는다** (Phase 2 재검토 대상). 새 기능도 이 패턴을 따른다.

- PHP 8.3+ (`composer.json`), Laravel 13, Laravel Breeze 2.4
- Node 22 (CI 기준), Vite 8, Tailwind 3 (`darkMode: 'class'`) + `@tailwindcss/vite` + `@tailwindcss/forms`
- 로컬 DB는 MySQL 8 (.env `DB_PORT=3307`, DB `saju_intro_platform`); 테스트는 in-memory SQLite (`phpunit.xml`)

## Common commands

```bash
# 개발 서버 (php serve + queue + pail 로그 + vite를 concurrently로 실행)
composer dev

# 프론트엔드만
npm run dev
npm run build

# 전체 테스트 (config:clear 후 artisan test)
composer test
# 단일 테스트
php artisan test --filter=ProfileTest
php artisan test tests/Feature/ProfileTest.php

# 린터 (Pint, Laravel 표준)
vendor/bin/pint
vendor/bin/pint --test   # 변경 없이 검사만

# DB 초기화 + 시드 (모든 콘텐츠 테이블 truncate 후 재시드 — 아래 "시딩" 섹션 주의)
php artisan migrate:fresh --seed
php artisan db:seed
```

## Architecture

### Routing and roles

- 전 라우트는 `routes/web.php` 하나에 정의 (관리자는 `->prefix('admin')->middleware(['auth','admin'])` 그룹).
- 미들웨어 alias는 `bootstrap/app.php`에서 등록: `admin` → `AdminMiddleware` (단순 `user()->role === 'admin'` 체크), `onboarding` → `EnsureOnboardingCompleted`.
- 역할은 `users.role` 단일 컬럼(`admin`/일반)으로 판단. `plan.md`의 Editor 역할은 아직 구현되지 않음.
- 비로그인 공개 경로: `/tracks`, `/tracks/{slug}`, `/hanja/{slug}`, `/dictionary`, `/lab/sample-chart*`, `/exam*`. 진행 저장·북마크·복습·관리자 기능은 `auth` 그룹.

### Layers

- `app/Http/Controllers/*` — 도메인별 1컨트롤러 (Tracks, Lessons, Quiz, Review, Lab 등), 관리자용은 `Admin/*`에 `Route::resource`로 노출.
- `app/Services/` — 지금은 `QuizService`, `ReviewService`만. 채점·SM-2 기반 복습 큐 생성 등 컨트롤러에서 빠지는 로직은 여기에 추가할 것 (`plan.md` §6 참고).
- `app/Models/` — 21개 Eloquent 모델. 학습 도메인 관계는 `plan.md` §4.2 다이어그램이 정답. `User`, `LearningTrack`, `Lesson`은 `SoftDeletes`.
- `resources/views/` — 도메인 폴더별(lessons/, hanja/, quiz/, review/, lab/, admin/, …) Blade 템플릿. 레이아웃은 `layouts/app.blade.php`(사용자) / `layouts/admin.blade.php`(관리자) / `layouts/guest.blade.php`(인증). 다크모드는 `<html class="dark">` 토글 + `localStorage.darkMode`로 FOUC 없이 초기화.

### Learning domain model

학습 진행은 `track_enrollments` → `lesson_attempts` → `lesson_steps`의 step-type 기반 플로우(intro/explanation/stroke_order/guided_practice/quiz/summary). 쓰기 연습은 `practice_sessions` + `practice_strokes`(Pointer Events 좌표 로그). 복습은 SM-2 기반 `review_cards`(stage: new/learning/reviewing/lapsed/mastered)를 퀴즈 오답 또는 자기평가 "어려움"에서 생성. 한자-그룹 M:N, 레슨-한자 M:N은 각각 `hanja_group_links`, `lesson_hanja_links` 피벗을 사용. 자세한 계약은 `plan.md` §4, §6.

## 시딩 (주의)

`DatabaseSeeder::run()`은 실행할 때마다 **콘텐츠 테이블 전체를 truncate 후 재시드**한다 (`users`/`profiles`/`sessions` 등 사용자 데이터는 보존). `db:seed`를 운영/공유 환경에서 돌리기 전에는 파급을 의식할 것. 시더 호출 순서는 `DatabaseSeeder.php` 기준으로 의존관계가 엮여 있으므로 새 시더는 기존 순서 끝에 추가하거나 의존 시더 뒤에 넣는다. 초기 관리자는 `admin@example.com` / `password`, 데모 회원은 `demo@example.com` / `password`.

## Deployment

- `push main` → `.github/workflows/deploy.yml`이 EC2에 **Capistrano 스타일 릴리스 폴더**로 rsync 배포(`/var/www/php/saju_learning/releases/<ts>` → `current` 심볼릭 링크). 실패 시 직전 릴리스로 자동 롤백, 릴리스는 최근 3개 보존.
- 배포 시 자동 실행: `composer install --no-dev`, 캐시 클리어, `migrate --force`, `db:seed --force`(위 truncate 동작 포함), `storage:link`.
- 운영 `.env`는 `.env.prod`를 리포에 커밋 → 배포 단계에서 `shared/.env`로 복사. 비밀값(DB 패스워드 등)이 이 파일에 들어가 있으므로 변경 시 주의.

## 언어

코드 내 주석과 PR 메시지, 사용자 응답은 한국어가 기본. 문서(`docs/`, `plan.md`)도 한국어.

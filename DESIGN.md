# 사주 입문 플랫폼 Design System

## 1. Atmosphere & Identity

사주와 한자를 처음 만나는 성인 학습자가 부담 없이 따라올 수 있는 조용한 학습실이다. 핵심 인상은 "차분한 단계감"이며, 흰색 또는 어두운 slate 표면 위에 얕은 그림자, 얇은 테두리, 절제된 인디고/에메랄드 상태색으로 학습 흐름을 분명히 보여준다.

## 2. Color

### Palette

| Role | Tailwind token | Light | Dark | Usage |
| --- | --- | --- | --- | --- |
| Surface/page | `gray-100` | `bg-gray-100` | `dark:bg-slate-900` | 전체 배경 |
| Surface/card | `white` | `bg-white` | `dark:bg-slate-800` | 카드, 레슨 패널 |
| Surface/soft | `gray-50` | `bg-gray-50` | `dark:bg-slate-700/50` | 보조 박스 |
| Text/primary | `gray-800` | `text-gray-800` | `dark:text-white` | 제목, 주요 본문 |
| Text/secondary | `gray-600` | `text-gray-600` | `dark:text-slate-300` | 설명, 보조 본문 |
| Text/muted | `gray-500` | `text-gray-500` | `dark:text-slate-400` | 캡션, 힌트 |
| Border/default | `gray-200` | `border-gray-200` | `dark:border-slate-700` | 카드와 입력 경계 |
| Accent/primary | `indigo-600` | `bg-indigo-600` | `dark:text-indigo-300` | CTA, 진행률, 포커스 |
| Status/success | `emerald` | `bg-emerald-50 text-emerald-800 border-emerald-200` | `dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/30` | 완료, 연습 가능 상태 |
| Status/warning | `amber` | `bg-amber-50 text-amber-800 border-amber-200` | `dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-500/30` | 데이터 누락, 주의 안내 |
| Status/error | `red` | `text-red-600 border-red-200` | `dark:text-red-400 dark:border-red-500/30` | 오류, 삭제 액션 |

### Rules
- 새 색은 Tailwind 기본 팔레트의 의미 역할로만 추가한다.
- 학습 흐름의 주요 액션은 인디고, 긍정/연습 가능 상태는 에메랄드, 데이터 주의는 앰버를 쓴다.
- 다크모드는 `dark:` 변형으로 같은 의미 역할을 유지한다.

## 3. Typography

### Scale

| Level | Tailwind class | Weight | Usage |
| --- | --- | --- | --- |
| Page title | `text-xl` | `font-semibold` | 레슨/트랙 페이지 제목 |
| Section title | `text-base` | `font-semibold` | Step 제목, 카드 제목 |
| Body | `text-sm` | `font-normal` | 기본 설명과 안내 |
| Caption | `text-xs` | `font-medium` | 배지, 메타 정보 |
| Hanja display | `text-2xl` to `text-8xl font-serif` | normal | 한자 표시, 쓰기 가이드 |

### Font Stack
- Primary: Figtree, system sans-serif
- Hanja display: browser serif stack via `font-serif`

### Rules
- 학습 패널 내부 본문은 `text-sm`를 기본으로 한다.
- 한자 자체는 의미 구분을 위해 `font-serif`를 사용한다.
- 한국어 문구는 버튼/카드 안에서 줄바꿈되어도 의미 단위가 깨지지 않도록 짧게 쓴다.

## 4. Spacing & Layout

### Base Unit

모든 간격은 Tailwind 4px 스케일을 따른다.

| Token | Value | Usage |
| --- | --- | --- |
| `1` | 4px | 아이콘과 짧은 텍스트 사이 |
| `2` | 8px | 캡션/소형 그룹 |
| `3` | 12px | 버튼 내부, 그리드 gap |
| `4` | 16px | 기본 카드 padding |
| `6` | 24px | 레슨 본문 padding |
| `8` | 32px | 페이지 상하 padding |

### Grid
- 학습 본문 폭은 `max-w-4xl`를 기본으로 한다.
- 반복 카드/캔버스는 `grid-cols-2 sm:grid-cols-3 lg:grid-cols-4`처럼 좁은 화면부터 안정적으로 늘린다.
- 고정 형식 요소는 `aspect-square`와 명시적 canvas width/height를 함께 둔다.

### Rules
- 카드 안에 카드형 섹션을 중첩하지 않는다.
- 모바일에서 텍스트가 버튼이나 패널 경계를 밀지 않도록 `flex-wrap`, 짧은 문구, 안정적인 aspect ratio를 사용한다.

## 5. Components

### Lesson Step Card
- Structure: 흰색/슬레이트 표면, 얇은 하단 헤더 구분선, 본문 padding.
- Variants: intro, explanation, stroke_order, guided_practice, quiz, summary.
- States: 현재 step만 `x-show`로 노출하고 `transform`, `opacity` transition을 사용한다.
- Accessibility: 헤더 텍스트와 배지는 시각적 순서를 유지한다.

### Status Notice
- Structure: `rounded-lg border p-4` 안에 `text-sm font-medium` 문구.
- Variants: indigo objective, emerald practice-ready, amber data-warning, red error.
- Usage: 사용자가 다음 행동을 이해해야 하는 상태 메시지에만 사용한다.

### Practice Canvas Grid
- Structure: 컨트롤 행, 반응형 grid, 각 cell은 `aspect-square`와 실제 `<canvas>`로 구성.
- States: guide toggle, clear all, per-cell undo, per-cell clear.
- Accessibility: 버튼은 `title`로 동작을 보강하고, canvas 주변에 안내 문구를 함께 둔다.

## 6. Motion & Interaction

### Timing

| Type | Duration | Usage |
| --- | --- | --- |
| Micro | `transition` default | hover, clear/undo 버튼 |
| Standard | `duration-200` to `duration-300` | step 전환, 진행률 |

### Rules
- 레슨 전환은 `opacity`와 `translate-y`만 사용한다.
- canvas 입력은 Pointer Events를 사용하고 `touch-action:none`으로 터치 스크롤 충돌을 막는다.
- 모든 버튼은 hover/focus가 Tailwind 기본 상태로 식별되어야 한다.

## 7. Depth & Surface

### Strategy

혼합 전략을 쓴다: 학습 카드에는 얕은 shadow, 데이터/상태 박스에는 border와 배경색을 조합한다.

| Level | Tailwind class | Usage |
| --- | --- | --- |
| Subtle | `shadow-sm` | 레슨 카드, 진행률 패널 |
| Border | `border border-gray-200 dark:border-slate-700` | 입력, 캔버스, 상태 박스 |
| Emphasis | `border-2 hover:border-indigo-300` | 쓰기 캔버스 cell |

### Rules
- 그림자는 큰 장식이 아니라 표면 분리에만 사용한다.
- 상태성 메시지는 색 배경과 border를 함께 써서 다크모드에서도 의미가 유지되게 한다.

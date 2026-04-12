# 사주입문플랫폼 Laravel Models + Factories 세트

이 패키지는 아래 구성을 포함합니다.

- `app/Models/*` : Eloquent 모델 세트
- `database/factories/*` : 핵심 팩토리 5종 + 보조 1종

## 포함된 모델

- User
- Profile
- LearningTrack
- TrackEnrollment
- Lesson
- LessonStep
- HanjaGroup
- HanjaChar
- HanjaGroupLink
- LessonHanjaLink
- StrokeTemplate
- PracticeSession
- PracticeStroke
- QuizSet
- QuizItem
- LessonAttempt
- ReviewCard
- ReviewLog
- Bookmark
- SajuExample
- AdminAuditLog

## 포함된 Factory

핵심 5종
- UserFactory
- LessonFactory
- HanjaCharFactory
- ReviewCardFactory
- PracticeSessionFactory

보조 1종
- LearningTrackFactory

## 특징

- `fillable` 반영
- JSON 컬럼 `casts` 반영
- `SoftDeletes` 반영
- 실무에서 자주 쓰는 기본 관계 메서드 포함
- `belongsToMany` 피벗 메타데이터 포함

## 설치 방법

1. `app/Models/*` 파일을 Laravel 프로젝트의 `app/Models`에 복사
2. `database/factories/*` 파일을 Laravel 프로젝트의 `database/factories`에 복사
3. migration과 seeder가 이미 적용된 상태에서 사용

## 예시

```php
use App\Models\Lesson;
use App\Models\User;

$lesson = Lesson::with(['learningTrack', 'steps', 'hanjaChars'])->first();
$user = User::with(['profile', 'reviewCards.hanjaChar'])->first();
```

## 메모

- `Bookmark`, `ReviewCard`는 `target_type`, `source_type`가 범용 문자열이라 `morphTo()` 대신 단순 필드 방식으로 두었습니다.
- `PracticeStroke`, `Bookmark`, `AdminAuditLog`, `ReviewLog`, `HanjaGroupLink`, `LessonHanjaLink`는 테이블 구조상 `updated_at`가 없어 `$timestamps = false`로 처리했습니다.
- `LessonFactory`, `PracticeSessionFactory`, `ReviewCardFactory`는 독립 실행을 위해 필요한 연관 모델을 자동 생성합니다.

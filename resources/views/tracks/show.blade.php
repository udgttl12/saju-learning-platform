<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $track->title }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ $track->short_description }}</p>
            </div>
            <a href="{{ route('tracks.index') }}" class="text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white">
                &larr; 트랙 목록
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-400">
                    {{ session('error') }}
                </div>
            @endif

            @if(!$trackState['unlocked'])
                <div class="rounded-lg border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-slate-600 dark:bg-slate-700/50 dark:text-slate-200">
                    {{ $trackState['reason'] }}
                </div>
            @endif

            <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 dark:shadow-slate-900/50">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-slate-400">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $track->difficulty_level <= 1 ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $track->difficulty_level == 2 ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $track->difficulty_level >= 3 ? 'bg-rose-100 text-rose-700' : '' }}">
                                @if($track->difficulty_level <= 1)
                                    입문
                                @elseif($track->difficulty_level == 2)
                                    초급
                                @else
                                    심화
                                @endif
                            </span>
                            <span>레슨 {{ $track->lessons->count() }}개</span>
                            @if($track->estimated_total_minutes)
                                <span>예상 {{ $track->estimated_total_minutes }}분</span>
                            @endif
                        </div>

                        @if($track->target_audience)
                            <p class="text-sm text-gray-600 dark:text-slate-300">
                                대상: {{ $track->target_audience }}
                            </p>
                        @endif
                    </div>

                    @auth
                        @if($enrollment)
                            <div class="text-right">
                                <div class="mb-1 text-sm text-gray-500 dark:text-slate-400">
                                    진행률 {{ number_format($enrollment->progress_percent, 0) }}%
                                </div>
                                <div class="h-2.5 w-48 rounded-full bg-gray-200 dark:bg-slate-700">
                                    <div class="h-2.5 rounded-full bg-indigo-600" style="width: {{ $enrollment->progress_percent }}%"></div>
                                </div>
                                @if($trackExamSet)
                                    <div class="mt-2 text-xs {{ $enrollment->passed_exam_at ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $enrollment->passed_exam_at ? '트랙 시험 통과 완료' : '트랙 시험이 남아 있어요.' }}
                                    </div>
                                @endif
                            </div>
                        @else
                            @if($trackState['unlocked'])
                                <form action="{{ route('tracks.enroll', $track->slug) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                        트랙 등록하고 시작
                                    </button>
                                </form>
                            @else
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    선행 조건을 완료하면 등록할 수 있어요.
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="flex flex-col items-start gap-3">
                            @if($trackState['unlocked'] && $track->lessons->isNotEmpty())
                                <a href="{{ route('lessons.show', $track->lessons->first()->slug) }}"
                                   class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                    비회원으로 바로 학습
                                </a>
                            @endif
                            <form action="{{ route('guest.login') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                                <button type="submit"
                                        class="inline-flex items-center rounded-lg border border-indigo-200 px-5 py-2 text-sm font-medium text-indigo-700 transition hover:bg-indigo-50 dark:border-indigo-500/40 dark:text-indigo-300 dark:hover:bg-indigo-500/10">
                                    게스트 로그인
                                </button>
                            </form>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                비회원 학습 기록은 이 브라우저에만 임시 저장됩니다.
                            </p>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 dark:shadow-slate-900/50">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">레슨 목록</h3>

                <div class="space-y-3">
                    @foreach($track->lessons as $index => $lesson)
                        @php
                            $isCompleted = in_array($lesson->id, $completedLessonIds, true);
                            $lessonState = $lessonStates[$lesson->id] ?? ['unlocked' => $index === 0];
                            $canAccessLesson = auth()->guest()
                                ? ($trackState['unlocked'] && $lessonState['unlocked'])
                                : ($enrollment && $lessonState['unlocked']);
                            $isLocked = !$canAccessLesson;
                        @endphp

                        @if($canAccessLesson)
                            <a href="{{ route('lessons.show', $lesson->slug) }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all hover:border-indigo-400 hover:bg-indigo-50/50 hover:shadow-sm dark:border-slate-700 dark:hover:bg-slate-700/50">
                        @else
                            <div class="flex items-center rounded-lg border border-gray-200 p-4 opacity-60 transition dark:border-slate-700">
                        @endif
                            <div class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full
                                {{ $isCompleted ? 'bg-emerald-100 text-emerald-600' : ($isLocked ? 'bg-gray-100 text-gray-400' : 'bg-indigo-100 text-indigo-600') }}">
                                @if($isCompleted)
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($isLocked)
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 transition group-hover:text-indigo-600 dark:text-white">
                                    {{ $lesson->title }}
                                </div>
                                @if($lesson->objective)
                                    <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-slate-400">{{ $lesson->objective }}</p>
                                @endif
                                @if($isLocked && !empty($lessonState['reason']))
                                    <p class="mt-1 text-xs text-rose-500 dark:text-rose-400">{{ $lessonState['reason'] }}</p>
                                @endif
                            </div>

                            <div class="ml-4 flex flex-shrink-0 items-center gap-3 text-xs text-gray-400 dark:text-slate-500">
                                @if($lesson->estimated_minutes)
                                    <span>{{ $lesson->estimated_minutes }}분</span>
                                @endif
                                @if($canAccessLesson)
                                    <svg class="h-4 w-4 text-gray-300 transition group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                @endif
                            </div>
                        @if($canAccessLesson)
                            </a>
                        @else
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            @guest
                @if($trackExamSet)
                    <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 dark:shadow-slate-900/50">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">트랙 최종 시험</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                                    레슨과 단원 퀴즈는 비회원으로 체험할 수 있고, 최종 시험과 복습 카드는 로그인 후 이어서 사용할 수 있어요.
                                </p>
                            </div>
                            <form action="{{ route('guest.login') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                                <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                    게스트 로그인 후 계속하기
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endguest

            @if($enrollment && $trackExamSet)
                <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 dark:shadow-slate-900/50">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">트랙 최종 시험</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ $trackExamSet->title }}</p>
                            @if($trackExamSet->description)
                                <p class="mt-2 text-sm text-gray-600 dark:text-slate-300">{{ $trackExamSet->description }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-3 text-xs text-gray-500 dark:text-slate-400">
                                <span>합격 기준 {{ $trackExamSet->pass_score }}%</span>
                                @if($trackExamAttempt)
                                    <span>최고 점수 {{ $trackExamAttempt->score_percentage }}%</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-start gap-2 md:items-end">
                            @if($enrollment->passed_exam_at)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                    통과 완료
                                </span>
                            @elseif($allLessonsCompleted)
                                <a href="{{ route('quiz.show', $trackExamSet->code) }}"
                                   class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                    트랙 시험 시작
                                </a>
                            @else
                                <span class="text-sm text-amber-600 dark:text-amber-400">모든 레슨을 완료하면 열립니다.</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

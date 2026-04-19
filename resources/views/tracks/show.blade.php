<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $track->title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ $track->short_description }}</p>
            </div>
            <a href="{{ route('tracks.index') }}" class="text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white">&larr; 트랙 목록</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 플래시 메시지 --}}
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if(!$trackState['unlocked'])
                <div class="bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 px-4 py-3 rounded-lg text-sm">
                    {{ $trackState['reason'] }}
                </div>
            @endif

            {{-- 트랙 정보 카드 --}}
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-slate-400">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $track->difficulty_level <= 1 ? 'bg-green-100 text-green-700' : '' }}
                                {{ $track->difficulty_level == 2 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $track->difficulty_level >= 3 ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                @if($track->difficulty_level <= 1) 입문
                                @elseif($track->difficulty_level == 2) 중급
                                @else 고급
                                @endif
                            </span>
                            <span>{{ $track->lessons->count() }}개 레슨</span>
                            @if($track->estimated_total_minutes)
                                <span>약 {{ $track->estimated_total_minutes }}분</span>
                            @endif
                        </div>
                        @if($track->target_audience)
                            <p class="text-sm text-gray-600 dark:text-slate-300">대상: {{ $track->target_audience }}</p>
                        @endif
                    </div>

                    @auth
                        @if($enrollment)
                            <div class="text-right">
                                <div class="text-sm text-gray-500 dark:text-slate-400 mb-1">진행률 {{ number_format($enrollment->progress_percent, 0) }}%</div>
                                <div class="w-48 bg-gray-200 dark:bg-slate-700 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percent }}%"></div>
                                </div>
                                @if($trackExamSet)
                                    <div class="mt-2 text-xs {{ $enrollment->passed_exam_at ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $enrollment->passed_exam_at ? '트랙 시험 통과 완료' : '트랙 시험 대기 중' }}
                                    </div>
                                @endif
                            </div>
                        @else
                            @if($trackState['unlocked'])
                                <form action="{{ route('tracks.enroll', $track->slug) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                        학습 시작하기
                                    </button>
                                </form>
                            @else
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    선행 트랙을 마치면 시작할 수 있습니다.
                                </div>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            로그인 후 시작하기
                        </a>
                    @endauth
                </div>
            </div>

            {{-- 레슨 목록 --}}
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">레슨 목록</h3>
                <div class="space-y-3">
                    @foreach($track->lessons as $index => $lesson)
                        @php
                            $isCompleted = in_array($lesson->id, $completedLessonIds);
                            $lessonState = $lessonStates[$lesson->id] ?? ['unlocked' => $index === 0];
                            $isLocked = !$enrollment || !$lessonState['unlocked'];
                        @endphp

                        @if(!$isLocked && $enrollment)
                        <a href="{{ route('lessons.show', $lesson->slug) }}"
                           class="flex items-center border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:border-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-700/50 hover:shadow-sm transition-all cursor-pointer group">
                        @else
                        <div class="flex items-center border border-gray-200 dark:border-slate-700 rounded-lg p-4 {{ $isLocked ? 'opacity-50' : '' }} transition">
                        @endif
                            {{-- 번호 / 상태 아이콘 --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-4
                                {{ $isCompleted ? 'bg-emerald-100 text-emerald-600' : ($isLocked ? 'bg-gray-100 text-gray-400' : 'bg-indigo-100 text-indigo-600') }}">
                                @if($isCompleted)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($isLocked)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>

                            {{-- 레슨 정보 --}}
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 transition">{{ $lesson->title }}</span>
                                @if($lesson->objective)
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 truncate">{{ $lesson->objective }}</p>
                                @endif
                                @if($isLocked && !empty($lessonState['reason']))
                                    <p class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $lessonState['reason'] }}</p>
                                @endif
                            </div>

                            {{-- 메타 + 화살표 --}}
                            <div class="flex-shrink-0 flex items-center gap-3 text-xs text-gray-400 dark:text-slate-500 ml-4">
                                @if($lesson->estimated_minutes)
                                    <span>{{ $lesson->estimated_minutes }}분</span>
                                @endif
                                @if(!$isLocked && $enrollment)
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                @endif
                            </div>
                        @if(!$isLocked && $enrollment)
                        </a>
                        @else
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            @if($enrollment && $trackExamSet)
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">트랙 종료 시험</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ $trackExamSet->title }}</p>
                            @if($trackExamSet->description)
                                <p class="text-sm text-gray-600 dark:text-slate-300 mt-2">{{ $trackExamSet->description }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-3 text-xs text-gray-500 dark:text-slate-400">
                                <span>합격 기준 {{ $trackExamSet->pass_score }}%</span>
                                @if($trackExamAttempt)
                                    <span>최고 점수 {{ $trackExamAttempt->score_percentage }}%</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-start md:items-end gap-2">
                            @if($enrollment->passed_exam_at)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm font-medium">
                                    통과 완료
                                </span>
                            @elseif($allLessonsCompleted)
                                <a href="{{ route('quiz.show', $trackExamSet->code) }}"
                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    트랙 시험 보기
                                </a>
                            @else
                                <span class="text-sm text-amber-600 dark:text-amber-400">모든 레슨 완료 후 응시 가능</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

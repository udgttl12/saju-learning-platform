<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            퀴즈 결과 - {{ $quizSet->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($isGuestPreview)
                <div class="mb-6 rounded-lg border border-indigo-200 bg-indigo-50 p-4 dark:border-indigo-500/30 dark:bg-indigo-500/10">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-700 dark:text-indigo-300">
                                비회원 결과 화면이에요.
                            </p>
                            <p class="mt-1 text-xs text-indigo-600 dark:text-indigo-400">
                                오답 복습 카드와 북마크는 로그인 후 이어서 저장할 수 있어요.
                            </p>
                        </div>
                        <form action="{{ route('guest.login') }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                                게스트 로그인
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="mb-8 rounded-lg bg-white p-8 text-center shadow dark:bg-slate-800">
                <div class="text-5xl font-bold {{ $score['percentage'] >= $quizSet->pass_score ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $score['percentage'] }}%
                </div>
                <div class="mt-2 text-gray-500 dark:text-slate-400">
                    {{ $score['correct_count'] }} / {{ $score['total_items'] }} 정답
                    ({{ $score['earned_points'] }} / {{ $score['total_points'] }}점)
                </div>
                <div class="mt-3">
                    @if($score['percentage'] >= $quizSet->pass_score)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400">
                            합격
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-rose-100 px-4 py-2 text-sm font-medium text-rose-800 dark:bg-rose-500/20 dark:text-rose-400">
                            재도전 필요 (합격 기준 {{ $quizSet->pass_score }}%)
                        </span>
                    @endif
                </div>

                @if($createdReviewCards > 0)
                    <div class="mt-4 text-sm text-gray-600 dark:text-slate-300">
                        틀린 문제를 바탕으로 복습 카드 {{ $createdReviewCards }}개가 생성됐어요.
                    </div>
                @endif
            </div>

            @if($weakPoints->isNotEmpty())
                <div class="mb-8 rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">약한 개념</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($weakPoints as $weakPoint)
                            <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                                {{ $weakPoint['label'] }} <span class="font-semibold">x{{ $weakPoint['count'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($recommendedLessonMap->isNotEmpty())
                        <div class="mt-4">
                            <div class="mb-2 text-sm font-medium text-gray-700 dark:text-slate-200">추천 레슨</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($weakPoints as $weakPoint)
                                    @if(!empty($weakPoint['review_lesson_code']) && $recommendedLessonMap->has($weakPoint['review_lesson_code']))
                                        @php $lesson = $recommendedLessonMap[$weakPoint['review_lesson_code']]; @endphp
                                        <a href="{{ route('lessons.show', $lesson->slug) }}"
                                           class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1.5 text-sm text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20">
                                            {{ $lesson->title }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @foreach($results as $index => $result)
                <div class="mb-4 rounded-lg border-l-4 bg-white p-6 shadow dark:bg-slate-800 {{ $result['correct'] ? 'border-emerald-400' : 'border-rose-400' }}">
                    <div class="mb-3 flex items-start gap-3">
                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $result['correct'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $result['prompt_text'] }}</p>
                            <p class="mt-1 text-sm {{ $result['correct'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $result['correct'] ? '정답' : '오답' }}
                            </p>
                            @if(!$result['correct'] && !empty($result['correct_answer']))
                                <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">정답: {{ $result['correct_answer'] }}</p>
                            @endif
                        </div>
                    </div>

                    @if($result['explanation'])
                        <div class="mt-3 rounded bg-gray-50 p-3 text-sm text-gray-600 dark:bg-slate-700/50 dark:text-slate-300">
                            <span class="font-medium">해설:</span> {{ $result['explanation'] }}
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="mt-8 text-center">
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('quiz.show', $quizSet->code) }}"
                       class="rounded-lg bg-indigo-600 px-6 py-2 text-sm text-white hover:bg-indigo-700">
                        다시 풀기
                    </a>

                    @if(!$isGuestPreview)
                        <a href="{{ route('review.index') }}"
                           class="rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/50">
                            복습으로 이동
                        </a>
                    @else
                        <form action="{{ route('guest.login') }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ request()->getRequestUri() }}">
                            <button type="submit"
                                    class="rounded-lg border border-indigo-300 bg-white px-6 py-2 text-sm text-indigo-700 hover:bg-indigo-50 dark:border-indigo-500/40 dark:bg-slate-800 dark:text-indigo-300 dark:hover:bg-indigo-500/10">
                                게스트 로그인 후 저장
                            </button>
                        </form>
                    @endif

                    @if($quizSet->learningTrack)
                        <a href="{{ route('tracks.show', $quizSet->learningTrack->slug) }}"
                           class="rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/50">
                            트랙으로 돌아가기
                        </a>
                    @elseif($quizSet->lesson)
                        <a href="{{ route('lessons.show', $quizSet->lesson->slug) }}"
                           class="rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/50">
                            레슨으로 돌아가기
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

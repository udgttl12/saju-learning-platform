<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $quizSet->title }}
                </h2>
                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-indigo-700">
                        {{ $quizSet->scope_type === 'track' ? '트랙 시험' : ($quizSet->scope_type === 'review' ? '복습 퀴즈' : '레슨 퀴즈') }}
                    </span>
                    @if($quizSet->learningTrack)
                        <span>{{ $quizSet->learningTrack->title }}</span>
                    @elseif($quizSet->lesson)
                        <span>{{ $quizSet->lesson->title }}</span>
                    @endif
                </div>
            </div>

            @if($quizSet->learningTrack)
                <a href="{{ route('tracks.show', $quizSet->learningTrack->slug) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-white">
                    트랙으로 돌아가기
                </a>
            @elseif($quizSet->lesson)
                <a href="{{ route('lessons.show', $quizSet->lesson->slug) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-white">
                    레슨으로 돌아가기
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($quizSet->description)
                <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                    {{ $quizSet->description }}
                </div>
            @endif

            @if($isGuestPreview)
                <div class="mb-6 rounded-lg border border-indigo-200 bg-indigo-50 p-4 dark:border-indigo-500/30 dark:bg-indigo-500/10">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-700 dark:text-indigo-300">
                                비회원으로 퀴즈를 체험 중이에요.
                            </p>
                            <p class="mt-1 text-xs text-indigo-600 dark:text-indigo-400">
                                결과는 이 브라우저에 임시 저장되고, 복습 카드와 북마크는 로그인 후 사용할 수 있어요.
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

            @if($bestAttempt)
                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                    지난 최고 점수는 <span class="font-semibold">{{ $bestAttempt->score_percentage }}%</span>예요.
                    @if($bestAttempt->passed)
                        이미 합격 기준을 넘겼어요.
                    @else
                        다시 도전해서 더 높은 점수를 받아보세요.
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('quiz.submit', $quizSet->code) }}">
                @csrf

                @foreach($quizSet->items as $index => $item)
                    <div class="mb-6 rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                        <div class="mb-4 flex items-start gap-3">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item->prompt_text }}</p>
                                @if($item->hint_text)
                                    <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">힌트: {{ $item->hint_text }}</p>
                                @endif
                            </div>
                        </div>

                        @if($item->question_type === 'multiple_choice')
                            @foreach($item->choices_json ?? [] as $choiceIndex => $choice)
                                <label class="mb-2 flex cursor-pointer items-center rounded-md border border-gray-200 p-3 hover:bg-gray-50 dark:border-slate-700 dark:hover:bg-slate-700/50">
                                    <input type="radio" name="answers[{{ $item->id }}]" value="{{ $choiceIndex }}" class="mr-3 text-indigo-600">
                                    <span class="text-sm text-gray-700 dark:text-slate-200">{{ $choice }}</span>
                                </label>
                            @endforeach
                        @elseif($item->question_type === 'true_false')
                            <label class="mb-2 flex cursor-pointer items-center rounded-md border border-gray-200 p-3 hover:bg-gray-50 dark:border-slate-700 dark:hover:bg-slate-700/50">
                                <input type="radio" name="answers[{{ $item->id }}]" value="1" class="mr-3 text-indigo-600">
                                <span class="text-sm text-gray-700 dark:text-slate-200">O</span>
                            </label>
                            <label class="mb-2 flex cursor-pointer items-center rounded-md border border-gray-200 p-3 hover:bg-gray-50 dark:border-slate-700 dark:hover:bg-slate-700/50">
                                <input type="radio" name="answers[{{ $item->id }}]" value="0" class="mr-3 text-indigo-600">
                                <span class="text-sm text-gray-700 dark:text-slate-200">X</span>
                            </label>
                        @elseif($item->question_type === 'short_answer')
                            <input type="text"
                                   name="answers[{{ $item->id }}]"
                                   placeholder="정답을 입력하세요"
                                   class="w-full rounded-md border-gray-300 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @elseif($item->question_type === 'self_check')
                            <textarea name="answers[{{ $item->id }}]"
                                      rows="3"
                                      placeholder="생각한 내용을 간단히 적어보세요"
                                      class="w-full rounded-md border-gray-300 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
                            <p class="mt-2 text-sm italic text-gray-500 dark:text-slate-400">
                                자기 점검 문제는 정답 여부보다 스스로 떠올려보는 과정이 중요해요.
                            </p>
                        @endif
                    </div>
                @endforeach

                <div class="text-center">
                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-8 py-3 text-sm font-medium text-white hover:bg-indigo-700">
                        결과 확인하기
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

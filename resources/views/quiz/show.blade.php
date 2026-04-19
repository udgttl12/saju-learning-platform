<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $quizSet->title }}
                </h2>
                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">
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
                <a href="{{ route('tracks.show', $quizSet->learningTrack->slug) }}" class="text-sm text-gray-500 hover:text-gray-700">
                    트랙으로
                </a>
            @elseif($quizSet->lesson)
                <a href="{{ route('lessons.show', $quizSet->lesson->slug) }}" class="text-sm text-gray-500 hover:text-gray-700">
                    레슨으로
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($quizSet->description)
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg text-sm">
                    {{ $quizSet->description }}
                </div>
            @endif

            @if($bestAttempt)
                <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-lg text-sm">
                    이전 최고 점수는 <span class="font-semibold">{{ $bestAttempt->score_percentage }}%</span>입니다.
                    @if($bestAttempt->passed)
                        이미 합격한 퀴즈지만 약점 보완용으로 다시 풀 수 있습니다.
                    @else
                        이번에는 헷갈린 개념을 줄여보면 됩니다.
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('quiz.submit', $quizSet->code) }}">
                @csrf

                @foreach($quizSet->items as $index => $item)
                <div class="bg-white rounded-lg shadow mb-6 p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <span class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-sm font-bold">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="text-gray-900 font-medium">{{ $item->prompt_text }}</p>
                            @if($item->hint_text)
                                <p class="text-xs text-gray-400 mt-1">힌트: {{ $item->hint_text }}</p>
                            @endif
                        </div>
                    </div>

                    @if($item->question_type === 'multiple_choice')
                        @foreach($item->choices_json ?? [] as $ci => $choice)
                        <label class="flex items-center p-3 border border-gray-200 rounded-md mb-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="answers[{{ $item->id }}]" value="{{ $ci }}" class="mr-3 text-indigo-600">
                            <span class="text-sm text-gray-700">{{ $choice }}</span>
                        </label>
                        @endforeach

                    @elseif($item->question_type === 'true_false')
                        <label class="flex items-center p-3 border border-gray-200 rounded-md mb-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="answers[{{ $item->id }}]" value="1" class="mr-3 text-indigo-600">
                            <span class="text-sm text-gray-700">O (맞다)</span>
                        </label>
                        <label class="flex items-center p-3 border border-gray-200 rounded-md mb-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="answers[{{ $item->id }}]" value="0" class="mr-3 text-indigo-600">
                            <span class="text-sm text-gray-700">X (틀리다)</span>
                        </label>

                    @elseif($item->question_type === 'short_answer')
                        <input type="text" name="answers[{{ $item->id }}]" placeholder="답을 입력하세요"
                               class="w-full rounded-md border-gray-300 shadow-sm text-sm">

                    @elseif($item->question_type === 'self_check')
                        <textarea name="answers[{{ $item->id }}]" rows="3" placeholder="한두 문장으로 적어보세요"
                                  class="w-full rounded-md border-gray-300 shadow-sm text-sm"></textarea>
                        <p class="text-sm text-gray-500 italic mt-2">자동 채점 대신 작성 완료를 기준으로 저장됩니다.</p>
                    @endif
                </div>
                @endforeach

                <div class="text-center">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg text-sm font-medium hover:bg-indigo-700">
                        제출하기
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

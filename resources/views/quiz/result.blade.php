<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            퀴즈 결과 - {{ $quizSet->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Score Summary --}}
            <div class="bg-white rounded-lg shadow p-8 mb-8 text-center">
                <div class="text-5xl font-bold {{ $score['percentage'] >= $quizSet->pass_score ? 'text-green-600' : 'text-red-600' }}">
                    {{ $score['percentage'] }}%
                </div>
                <div class="mt-2 text-gray-500">
                    {{ $score['correct_count'] }} / {{ $score['total_items'] }} 정답
                    ({{ $score['earned_points'] }} / {{ $score['total_points'] }} 점)
                </div>
                <div class="mt-3">
                    @if($score['percentage'] >= $quizSet->pass_score)
                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">합격</span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">불합격 (합격 기준: {{ $quizSet->pass_score }}%)</span>
                    @endif
                </div>
                @if($createdReviewCards > 0)
                    <div class="mt-4 text-sm text-gray-600">
                        헷갈린 개념 {{ $createdReviewCards }}건이 복습 카드에 추가되었습니다.
                    </div>
                @endif
            </div>

            @if($attempt && !empty($attempt->weak_points_json))
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">약한 개념</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($attempt->weak_points_json as $weakPoint)
                            <div class="px-3 py-2 rounded-lg bg-amber-50 border border-amber-200 text-sm text-amber-800">
                                {{ $weakPoint['label'] }} <span class="font-semibold">x{{ $weakPoint['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if($recommendedLessonMap->isNotEmpty())
                        <div class="mt-4">
                            <div class="text-sm font-medium text-gray-700 mb-2">다시 보면 좋은 레슨</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($attempt->weak_points_json as $weakPoint)
                                    @if(!empty($weakPoint['review_lesson_code']) && $recommendedLessonMap->has($weakPoint['review_lesson_code']))
                                        @php $lesson = $recommendedLessonMap[$weakPoint['review_lesson_code']]; @endphp
                                        <a href="{{ route('lessons.show', $lesson->slug) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-sm hover:bg-indigo-100">
                                            {{ $lesson->title }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Detail Results --}}
            @foreach($results as $index => $result)
            <div class="bg-white rounded-lg shadow mb-4 p-6 {{ $result['correct'] ? 'border-l-4 border-green-400' : 'border-l-4 border-red-400' }}">
                <div class="flex items-start gap-3 mb-3">
                    <span class="flex-shrink-0 w-8 h-8 {{ $result['correct'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full flex items-center justify-center text-sm font-bold">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <p class="text-gray-900 font-medium">{{ $result['prompt_text'] }}</p>
                        <p class="text-sm mt-1 {{ $result['correct'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $result['correct'] ? '정답' : '오답' }}
                        </p>
                        @if(!$result['correct'] && !empty($result['correct_answer']))
                            <p class="text-sm text-gray-500 mt-2">정답: {{ $result['correct_answer'] }}</p>
                        @endif
                    </div>
                </div>
                @if($result['explanation'])
                    <div class="mt-3 bg-gray-50 p-3 rounded text-sm text-gray-600">
                        <span class="font-medium">해설:</span> {{ $result['explanation'] }}
                    </div>
                @endif
            </div>
            @endforeach

            <div class="mt-8 text-center">
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('quiz.show', $quizSet->code) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700">
                        다시 풀기
                    </a>
                    <a href="{{ route('review.index') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-50">
                        복습 보기
                    </a>
                    @if($quizSet->learningTrack)
                        <a href="{{ route('tracks.show', $quizSet->learningTrack->slug) }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-50">
                            트랙으로 돌아가기
                        </a>
                    @elseif($quizSet->lesson)
                        <a href="{{ route('lessons.show', $quizSet->lesson->slug) }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-50">
                            레슨으로 돌아가기
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

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
            </div>

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
                <a href="{{ route('quiz.show', $quizSet->code) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    다시 풀기
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

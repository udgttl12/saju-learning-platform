<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $quizSet->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($quizSet->description)
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg text-sm">
                    {{ $quizSet->description }}
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
                        <input type="hidden" name="answers[{{ $item->id }}]" value="checked">
                        <p class="text-sm text-gray-500 italic">스스로 확인하는 문항입니다.</p>
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

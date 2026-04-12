<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">시험</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-2xl mb-4">試</div>
                    <h3 class="text-xl font-bold text-gray-800">한자 뜻 맞히기</h3>
                    <p class="text-sm text-gray-500 mt-2">한자를 보고 뜻을 맞히는 4지 선다 시험입니다.<br>틀린 한자는 자동으로 복습 카드에 등록됩니다.</p>
                </div>

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('exam.start') }}" class="space-y-6">
                    @csrf

                    {{-- 카테고리 선택 --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">카테고리</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($categories as $key => $cat)
                                @if($cat['count'] >= 4)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="category" value="{{ $key }}" class="peer sr-only" {{ $key === 'all' ? 'checked' : '' }}>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl text-center transition
                                        peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-300">
                                        <div class="font-semibold text-gray-800">{{ $cat['label'] }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $cat['count'] }}자</div>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- 문제 수 선택 --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">문제 수</label>
                        <div class="flex gap-2">
                            @foreach([5, 10, 15, 20, 27] as $n)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="count" value="{{ $n }}" class="peer sr-only" {{ $n === 10 ? 'checked' : '' }}>
                                <div class="py-3 border-2 border-gray-200 rounded-xl text-center text-sm font-semibold transition
                                    peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-300">
                                    {{ $n }}문제
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-xl text-lg hover:bg-indigo-700 transition shadow-lg">
                        시험 시작
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

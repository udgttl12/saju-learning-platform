<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            복습 카드
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8" x-data="{ revealed: false }">
                {{-- Front: Question --}}
                <div class="text-center mb-8">
                    <div class="text-7xl font-bold text-gray-900 mb-4">
                        {{ $card->hanjaChar?->char_value }}
                    </div>
                    <p class="text-sm text-gray-400">이 한자의 음과 뜻을 떠올려 보세요</p>
                </div>

                {{-- Reveal button --}}
                <div class="text-center mb-6" x-show="!revealed">
                    <button @click="revealed = true" class="bg-gray-800 text-white px-8 py-3 rounded-lg text-sm font-medium hover:bg-gray-900">
                        답 확인하기
                    </button>
                </div>

                {{-- Back: Answer --}}
                <div x-show="revealed" x-transition class="border-t border-gray-200 pt-6">
                    <div class="text-center mb-6">
                        <div class="text-lg font-medium text-gray-900">{{ $card->hanjaChar?->reading_ko }}</div>
                        <div class="text-gray-600">{{ $card->hanjaChar?->meaning_ko }}</div>
                        @if($card->hanjaChar?->mnemonic_text)
                            <p class="mt-2 text-sm text-gray-500 italic">{{ $card->hanjaChar->mnemonic_text }}</p>
                        @endif
                        @if($card->hanjaChar?->usage_in_saju)
                            <p class="mt-2 text-sm text-gray-500">사주 활용: {{ $card->hanjaChar->usage_in_saju }}</p>
                        @endif
                    </div>

                    {{-- Answer buttons --}}
                    <div class="grid grid-cols-4 gap-3">
                        <form method="POST" action="{{ route('review.answer', $card->id) }}">
                            @csrf
                            <input type="hidden" name="result" value="again">
                            <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg text-sm font-medium hover:bg-red-600">
                                다시<br><span class="text-xs opacity-75">모르겠음</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('review.answer', $card->id) }}">
                            @csrf
                            <input type="hidden" name="result" value="hard">
                            <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg text-sm font-medium hover:bg-orange-600">
                                어려움<br><span class="text-xs opacity-75">힘들게 기억</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('review.answer', $card->id) }}">
                            @csrf
                            <input type="hidden" name="result" value="good">
                            <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg text-sm font-medium hover:bg-green-600">
                                좋음<br><span class="text-xs opacity-75">기억남</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('review.answer', $card->id) }}">
                            @csrf
                            <input type="hidden" name="result" value="easy">
                            <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg text-sm font-medium hover:bg-blue-600">
                                쉬움<br><span class="text-xs opacity-75">완벽</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('review.index') }}" class="text-sm text-gray-500 hover:text-gray-700">복습 목록으로</a>
            </div>
        </div>
    </div>
</x-app-layout>

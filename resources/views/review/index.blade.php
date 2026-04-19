<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            복습
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-500/10 border border-green-400 dark:border-green-500/30 text-green-700 dark:text-green-400 px-4 py-3 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow dark:shadow-slate-900/50 p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">오늘 복습할 카드</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ $cards->count() }}개의 카드가 복습 대기 중입니다.</p>
            </div>

            @if($cards->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow dark:shadow-slate-900/50 p-12 text-center">
                    <div class="text-gray-400 dark:text-slate-500 text-lg">오늘 복습할 카드가 없습니다.</div>
                    <p class="text-sm text-gray-400 dark:text-slate-500 mt-2">학습을 계속하면 복습 카드가 자동으로 생성됩니다.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($cards as $card)
                    <a href="{{ route('review.show', $card->id) }}"
                       class="bg-white dark:bg-slate-800 rounded-lg shadow dark:shadow-slate-900/50 p-6 hover:shadow-md transition-shadow block">
                        <div class="text-center">
                            @if($card->isConceptCard())
                                <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-medium mb-3">
                                    개념 복습
                                </div>
                                <div class="text-base font-semibold text-gray-900 dark:text-white leading-relaxed">
                                    {{ $card->prompt_text }}
                                </div>
                                @if(!empty($card->meta_json['review_title']))
                                    <div class="text-sm text-gray-500 dark:text-slate-400 mt-2">
                                        {{ $card->meta_json['review_title'] }}
                                    </div>
                                @endif
                            @else
                                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $card->hanjaChar?->char_value }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-slate-400">
                                    {{ $card->hanjaChar?->reading_ko }} - {{ $card->hanjaChar?->meaning_ko }}
                                </div>
                            @endif
                            <div class="mt-3 flex items-center justify-center gap-2 text-xs text-gray-400 dark:text-slate-500">
                                <span>반복 {{ $card->repetitions }}회</span>
                                <span>|</span>
                                <span>{{ \App\Support\UiLabel::reviewStage($card->stage) }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

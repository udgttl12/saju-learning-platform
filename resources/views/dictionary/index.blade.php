<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            한자 사전
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 검색 --}}
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow dark:shadow-slate-900/50 p-4 mb-6">
                <form method="GET" class="flex items-center gap-3">
                    <input type="hidden" name="category" value="{{ $category }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="한자, 음, 뜻 검색"
                           class="rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm text-sm px-3 py-2 flex-1">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">검색</button>
                    @if(request('search'))
                        <a href="{{ route('dictionary.index', ['category' => $category]) }}" class="text-gray-500 text-sm hover:text-gray-700">초기화</a>
                    @endif
                </form>
            </div>

            {{-- 카테고리 탭 --}}
            @php
                $tabs = [
                    'all' => ['label' => '전체', 'count' => $totalCount],
                    'five_elements' => ['label' => '오행', 'count' => $categoryCounts['five_elements'] ?? 0],
                    'heavenly_stems' => ['label' => '천간', 'count' => $categoryCounts['heavenly_stems'] ?? 0],
                    'earthly_branches' => ['label' => '지지', 'count' => $categoryCounts['earthly_branches'] ?? 0],
                    'term' => ['label' => '용어', 'count' => $categoryCounts['term'] ?? 0],
                ];
            @endphp
            <div class="flex gap-1 mb-6 bg-white dark:bg-slate-800 rounded-lg shadow dark:shadow-slate-900/50 p-1.5 overflow-x-auto">
                @foreach($tabs as $key => $tab)
                    @if($key !== 'term' || $tab['count'] > 0)
                    <a href="{{ route('dictionary.index', array_merge(request()->only('search'), ['category' => $key])) }}"
                       class="flex items-center gap-2 px-5 py-2.5 rounded-md text-sm font-medium transition whitespace-nowrap
                       {{ $category === $key ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
                        {{ $tab['label'] }}
                        <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs
                            {{ $category === $key ? 'bg-indigo-500 text-indigo-100' : 'bg-gray-200 text-gray-500' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                    @endif
                @endforeach
            </div>

            {{-- 결과 --}}
            @if($hanjaChars->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow dark:shadow-slate-900/50 p-12 text-center">
                    <div class="text-gray-400 dark:text-slate-500 text-lg">검색 결과가 없습니다.</div>
                </div>
            @else
                @php
                    $elementColors = [
                        'wood'  => 'bg-green-100 text-green-700 border-green-200',
                        'fire'  => 'bg-red-100 text-red-700 border-red-200',
                        'earth' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'metal' => 'bg-gray-100 text-gray-700 border-gray-300',
                        'water' => 'bg-blue-100 text-blue-700 border-blue-200',
                    ];
                    $elementLabels = [
                        'wood' => '목(木)', 'fire' => '화(火)', 'earth' => '토(土)',
                        'metal' => '금(金)', 'water' => '수(水)',
                    ];
                    $yinYangLabels = ['yang' => '양', 'yin' => '음'];
                @endphp

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4">
                    @foreach($hanjaChars as $hanja)
                    <a href="{{ route('hanja.show', $hanja->slug) }}"
                       class="bg-white dark:bg-slate-800 rounded-xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 p-4 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-500/50 transition text-center block group">
                        <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition">{{ $hanja->char_value }}</div>
                        <div class="text-sm font-semibold text-gray-700 dark:text-slate-300">{{ $hanja->reading_ko }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mb-2">{{ $hanja->meaning_ko }}</div>
                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                            @if($hanja->element && $hanja->element !== 'none')
                                <span class="text-xs px-2 py-0.5 rounded-full border {{ $elementColors[$hanja->element] ?? '' }}">
                                    {{ $elementLabels[$hanja->element] ?? $hanja->element }}
                                </span>
                            @endif
                            @if($hanja->yin_yang && $hanja->yin_yang !== 'neutral')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    {{ $yinYangLabels[$hanja->yin_yang] ?? $hanja->yin_yang }}
                                </span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-6">{{ $hanjaChars->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                한자 카드: {{ $hanja->char_value }}
            </h2>
            <button onclick="history.back()" class="text-sm text-gray-500 hover:text-gray-700">&larr; 돌아가기</button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 메인 한자 카드 --}}
            @php
                $elementColors = [
                    'wood'  => ['bg' => 'bg-green-50',  'border' => 'border-green-300', 'text' => 'text-green-700',  'badge' => 'bg-green-100 text-green-800'],
                    'fire'  => ['bg' => 'bg-red-50',    'border' => 'border-red-300',   'text' => 'text-red-700',    'badge' => 'bg-red-100 text-red-800'],
                    'earth' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300','text' => 'text-yellow-700', 'badge' => 'bg-yellow-100 text-yellow-800'],
                    'metal' => ['bg' => 'bg-gray-50',   'border' => 'border-gray-300',  'text' => 'text-gray-700',   'badge' => 'bg-gray-200 text-gray-800'],
                    'water' => ['bg' => 'bg-blue-50',   'border' => 'border-blue-300',  'text' => 'text-blue-700',   'badge' => 'bg-blue-100 text-blue-800'],
                ];
                $colors = $elementColors[$hanja->element] ?? $elementColors['earth'];
                $elementKo = match($hanja->element) {
                    'wood' => '목(木)',
                    'fire' => '화(火)',
                    'earth' => '토(土)',
                    'metal' => '금(金)',
                    'water' => '수(水)',
                    default => $hanja->element,
                };
                $yinYangKo = match($hanja->yin_yang) {
                    'yin' => '음(陰)',
                    'yang' => '양(陽)',
                    default => $hanja->yin_yang,
                };
            @endphp

            <div class="{{ $colors['bg'] }} {{ $colors['border'] }} border-2 rounded-2xl p-8 text-center">
                {{-- 큰 한자 글자 --}}
                <div class="text-9xl font-serif {{ $colors['text'] }} mb-4 leading-none">{{ $hanja->char_value }}</div>

                {{-- 음독 & 훈독 --}}
                <div class="text-2xl font-semibold text-gray-800 mb-1">{{ $hanja->reading_ko }}</div>
                <div class="text-lg text-gray-600 mb-4">{{ $hanja->meaning_ko }}</div>

                {{-- 오행 & 음양 뱃지 --}}
                <div class="flex items-center justify-center gap-3">
                    @if($hanja->element)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors['badge'] }}">
                            {{ $elementKo }}
                        </span>
                    @endif
                    @if($hanja->yin_yang)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            {{ $yinYangKo }}
                        </span>
                    @endif
                    @if($hanja->stroke_count)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200">
                            {{ $hanja->stroke_count }}획
                        </span>
                    @endif
                </div>

                {{-- 즐겨찾기 버튼 --}}
                @auth
                    <div class="mt-6">
                        <form action="{{ route('bookmarks.toggle') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="target_type" value="hanja_char">
                            <input type="hidden" name="target_id" value="{{ $hanja->id }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                    {{ $isBookmarked ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                @if($isBookmarked)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                    즐겨찾기 해제
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                    즐겨찾기
                                @endif
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            {{-- 상세 정보 카드들 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 기억법 (Mnemonic) --}}
                @if($hanja->mnemonic_text)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">기억법</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $hanja->mnemonic_text }}</p>
                    </div>
                @endif

                {{-- 사주에서의 활용 --}}
                @if($hanja->usage_in_saju)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">사주에서의 활용</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $hanja->usage_in_saju }}</p>
                    </div>
                @endif

                {{-- 구조 설명 --}}
                @if($hanja->structure_note)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">구조 설명</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $hanja->structure_note }}</p>
                    </div>
                @endif

                {{-- 카테고리 --}}
                @if($hanja->category)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">카테고리</h3>
                        <p class="text-sm text-gray-700">{{ $hanja->category }}</p>
                    </div>
                @endif
            </div>

            {{-- 관련 레슨 --}}
            @if($hanja->lessons->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">관련 레슨</h3>
                    <div class="space-y-2">
                        @foreach($hanja->lessons as $lesson)
                            <a href="{{ route('lessons.show', $lesson->slug) }}"
                               class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-indigo-300 transition">
                                <span class="text-sm text-gray-800">{{ $lesson->title }}</span>
                                <span class="text-xs text-gray-400">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 관련 그룹 --}}
            @if($hanja->groups->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">소속 한자 그룹</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($hanja->groups as $group)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                {{ $group->title ?? $group->name ?? $group->code }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

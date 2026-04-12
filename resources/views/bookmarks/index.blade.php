<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            즐겨찾기
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($bookmarks->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <div class="text-gray-400 text-5xl mb-4">&#x2606;</div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">즐겨찾기가 비어 있습니다</h3>
                    <p class="text-sm text-gray-500">한자 카드나 레슨에서 즐겨찾기를 추가해보세요.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bookmarks as $bookmark)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            @if($bookmark->target_type === 'hanja_char' && $bookmark->target)
                                @php
                                    $hc = $bookmark->target;
                                    $elementBg = match($hc->element) {
                                        'wood' => 'bg-green-50',
                                        'fire' => 'bg-red-50',
                                        'earth' => 'bg-yellow-50',
                                        'metal' => 'bg-gray-50',
                                        'water' => 'bg-blue-50',
                                        default => 'bg-gray-50',
                                    };
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 {{ $elementBg }} rounded-lg flex items-center justify-center text-3xl font-serif">
                                        {{ $hc->char_value }}
                                    </div>
                                    <div class="flex-1">
                                        <a href="{{ route('hanja.show', $hc->slug) }}" class="text-base font-semibold text-gray-800 hover:text-indigo-600 transition">
                                            {{ $hc->reading_ko }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ $hc->meaning_ko }}</p>
                                        <span class="text-xs text-gray-400">한자</span>
                                    </div>
                                    <form action="{{ route('bookmarks.toggle') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="target_type" value="hanja_char">
                                        <input type="hidden" name="target_id" value="{{ $hc->id }}">
                                        <button type="submit" class="text-amber-500 hover:text-amber-600 transition" title="즐겨찾기 해제">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                        </button>
                                    </form>
                                </div>

                            @elseif($bookmark->target_type === 'lesson' && $bookmark->target)
                                @php $ls = $bookmark->target; @endphp
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <a href="{{ route('lessons.show', $ls->slug) }}" class="text-base font-semibold text-gray-800 hover:text-indigo-600 transition">
                                            {{ $ls->title }}
                                        </a>
                                        @if($ls->objective)
                                            <p class="text-sm text-gray-500 truncate">{{ $ls->objective }}</p>
                                        @endif
                                        <span class="text-xs text-gray-400">레슨</span>
                                    </div>
                                    <form action="{{ route('bookmarks.toggle') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="target_type" value="lesson">
                                        <input type="hidden" name="target_id" value="{{ $ls->id }}">
                                        <button type="submit" class="text-amber-500 hover:text-amber-600 transition" title="즐겨찾기 해제">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                        </button>
                                    </form>
                                </div>

                            @else
                                <div class="text-sm text-gray-400">삭제된 항목</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

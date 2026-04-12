<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            한자 사전
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Search & Filter --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="한자, 음, 뜻 검색"
                           class="rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 flex-1 min-w-48">
                    <select name="category" class="rounded-md border-gray-300 shadow-sm text-sm px-3 py-2">
                        <option value="">전체 카테고리</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select name="element" class="rounded-md border-gray-300 shadow-sm text-sm px-3 py-2">
                        <option value="">전체 오행</option>
                        @foreach($elements as $el)
                            <option value="{{ $el }}" {{ request('element') === $el ? 'selected' : '' }}>
                                {{ ['wood'=>'목(木)', 'fire'=>'화(火)', 'earth'=>'토(土)', 'metal'=>'금(金)', 'water'=>'수(水)'][$el] ?? $el }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">검색</button>
                    @if(request()->hasAny(['search', 'category', 'element']))
                        <a href="{{ route('dictionary.index') }}" class="text-gray-500 text-sm hover:text-gray-700">초기화</a>
                    @endif
                </form>
            </div>

            {{-- Results --}}
            @if($hanjaChars->isEmpty())
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <div class="text-gray-400 text-lg">검색 결과가 없습니다.</div>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($hanjaChars as $hanja)
                    <a href="{{ route('hanja.show', $hanja->slug) }}"
                       class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow text-center block">
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $hanja->char_value }}</div>
                        <div class="text-sm font-medium text-gray-700">{{ $hanja->reading_ko }}</div>
                        <div class="text-xs text-gray-500">{{ $hanja->meaning_ko }}</div>
                        @if($hanja->element)
                            <span class="mt-2 inline-block text-xs px-2 py-0.5 rounded-full
                                {{ $hanja->element === 'wood' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $hanja->element === 'fire' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $hanja->element === 'earth' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $hanja->element === 'metal' ? 'bg-gray-200 text-gray-700' : '' }}
                                {{ $hanja->element === 'water' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ['wood'=>'木', 'fire'=>'火', 'earth'=>'土', 'metal'=>'金', 'water'=>'水'][$hanja->element] ?? $hanja->element }}
                            </span>
                        @endif
                    </a>
                    @endforeach
                </div>

                <div class="mt-6">{{ $hanjaChars->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

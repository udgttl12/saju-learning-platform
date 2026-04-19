@extends('layouts.admin')

@section('title', '한자 관리')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <form method="GET" class="flex items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="검색 (한자, 음, 뜻)"
               class="rounded-md border-gray-300 shadow-sm text-sm px-3 py-2">
        <select name="category" class="rounded-md border-gray-300 shadow-sm text-sm px-3 py-2">
            <option value="">전체 카테고리</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ \App\Support\UiLabel::hanjaCategory($cat) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">검색</button>
    </form>
    <a href="{{ route('admin.hanja-chars.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
        한자 추가
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">한자</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">음</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">뜻</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">카테고리</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">오행</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($hanjaChars as $hanja)
            <tr>
                <td class="px-6 py-4 text-2xl font-bold">{{ $hanja->char_value }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $hanja->reading_ko }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $hanja->meaning_ko }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ \App\Support\UiLabel::hanjaCategory($hanja->category) }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ \App\Support\UiLabel::element($hanja->element) }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex text-xs px-2 py-1 rounded-full {{ $hanja->publish_status === 'published' ? 'bg-green-100 text-green-800' : ($hanja->publish_status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ \App\Support\UiLabel::publishStatus($hanja->publish_status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <a href="{{ route('admin.hanja-chars.edit', $hanja) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
                    <form method="POST" action="{{ route('admin.hanja-chars.destroy', $hanja) }}" class="inline" onsubmit="return confirm('정말 삭제하시겠습니까?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">삭제</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">등록된 한자가 없습니다.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $hanjaChars->links() }}
</div>
@endsection

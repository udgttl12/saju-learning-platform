@extends('layouts.admin')

@section('title', '레슨 관리')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <form method="GET" class="flex items-center gap-3">
        <select name="track_id" class="rounded-md border-gray-300 shadow-sm text-sm px-3 py-2">
            <option value="">전체 트랙</option>
            @foreach($tracks as $track)
                <option value="{{ $track->id }}" {{ request('track_id') == $track->id ? 'selected' : '' }}>{{ $track->title }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">필터</button>
    </form>
    <a href="{{ route('admin.lessons.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
        레슨 추가
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">제목</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">트랙</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">유형</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">난이도</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($lessons as $lesson)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $lesson->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $lesson->learningTrack?->title ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $lesson->lesson_type }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">Lv.{{ $lesson->difficulty_level }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex text-xs px-2 py-1 rounded-full {{ $lesson->publish_status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $lesson->publish_status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="inline" onsubmit="return confirm('정말 삭제하시겠습니까?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">삭제</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">등록된 레슨이 없습니다.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $lessons->links() }}</div>
@endsection

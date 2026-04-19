@extends('layouts.admin')

@section('title', '사주 예시 관리')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-lg font-medium text-gray-900">사주 예시 목록</h2>
    <a href="{{ route('admin.saju-examples.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
        예시 추가
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">제목</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">코드</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">성별</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">사주</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">난이도</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($examples as $ex)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $ex->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $ex->code }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $ex->gender === 'M' ? '남' : ($ex->gender === 'F' ? '여' : '-') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $ex->year_stem }}{{ $ex->year_branch }} {{ $ex->month_stem }}{{ $ex->month_branch }} {{ $ex->day_stem }}{{ $ex->day_branch }} {{ $ex->hour_stem }}{{ $ex->hour_branch }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">레벨 {{ $ex->difficulty_level }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex text-xs px-2 py-1 rounded-full {{ $ex->publish_status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ \App\Support\UiLabel::publishStatus($ex->publish_status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm space-x-2">
                    <a href="{{ route('admin.saju-examples.edit', $ex) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
                    <form method="POST" action="{{ route('admin.saju-examples.destroy', $ex) }}" class="inline" onsubmit="return confirm('정말 삭제하시겠습니까?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">삭제</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">등록된 사주 예시가 없습니다.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $examples->links() }}</div>
@endsection

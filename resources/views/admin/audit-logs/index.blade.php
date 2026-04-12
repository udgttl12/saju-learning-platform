@extends('layouts.admin')

@section('title', '감사 로그')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">시간</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">관리자</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">대상</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">액션</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($logs as $log)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $log->adminUser?->email ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $log->entity_type }} #{{ $log->entity_id }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex text-xs px-2 py-1 rounded-full
                        {{ $log->action_type === 'create' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $log->action_type === 'update' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $log->action_type === 'delete' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $log->action_type }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">감사 로그가 없습니다.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $logs->links() }}</div>
@endsection

@extends('layouts.admin')

@section('title', '대시보드')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">학습 트랙</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['tracks']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">레슨</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['lessons']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">한자</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['hanjas']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">회원</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['users']) }}</div>
    </div>
</div>
@endsection

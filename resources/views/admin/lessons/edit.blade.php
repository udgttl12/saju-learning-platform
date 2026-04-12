@extends('layouts.admin')

@section('title', '레슨 수정 - ' . $lesson->title)

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}">
        @csrf
        @method('PUT')
        @include('admin.lessons._form', ['lesson' => $lesson])
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md text-sm hover:bg-indigo-700">수정</button>
            <a href="{{ route('admin.lessons.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">취소</a>
        </div>
    </form>
</div>
@endsection

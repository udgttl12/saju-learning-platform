@extends('layouts.admin')

@section('title', '한자 수정 - ' . $hanjaChar->char_value)

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.hanja-chars.update', $hanjaChar) }}">
        @csrf
        @method('PUT')
        @include('admin.hanja-chars._form', ['hanjaChar' => $hanjaChar])
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md text-sm hover:bg-indigo-700">수정</button>
            <a href="{{ route('admin.hanja-chars.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">취소</a>
        </div>
    </form>
</div>
@endsection

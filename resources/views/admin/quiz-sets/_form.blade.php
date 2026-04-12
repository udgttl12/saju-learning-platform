@php $qs = $quizSet ?? null; @endphp

<div class="bg-white rounded-lg shadow p-6 space-y-4">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium text-gray-700">연결 레슨</label>
        <select name="lesson_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">없음</option>
            @foreach($lessons as $lesson)
                <option value="{{ $lesson->id }}" {{ old('lesson_id', $qs?->lesson_id) == $lesson->id ? 'selected' : '' }}>
                    {{ $lesson->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">코드 *</label>
            <input type="text" name="code" value="{{ old('code', $qs?->code) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">범위 유형</label>
            <input type="text" name="scope_type" value="{{ old('scope_type', $qs?->scope_type) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">제목 *</label>
        <input type="text" name="title" value="{{ old('title', $qs?->title) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">설명</label>
        <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $qs?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">난이도 (1~5) *</label>
            <input type="number" name="difficulty_level" value="{{ old('difficulty_level', $qs?->difficulty_level ?? 1) }}" min="1" max="5" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">합격 점수 (%) *</label>
            <input type="number" name="pass_score" value="{{ old('pass_score', $qs?->pass_score ?? 70) }}" min="0" max="100" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">공개 상태 *</label>
            <select name="publish_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="draft" {{ old('publish_status', $qs?->publish_status ?? 'draft') === 'draft' ? 'selected' : '' }}>임시저장</option>
                <option value="published" {{ old('publish_status', $qs?->publish_status) === 'published' ? 'selected' : '' }}>공개</option>
                <option value="archived" {{ old('publish_status', $qs?->publish_status) === 'archived' ? 'selected' : '' }}>보관</option>
            </select>
        </div>
    </div>
</div>

@if($qs && $qs->items->count())
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">문항 목록 ({{ $qs->items->count() }}개)</h3>
    <div class="space-y-2">
        @foreach($qs->items as $item)
        <div class="border border-gray-200 rounded p-3 text-sm">
            <span class="font-medium">#{{ $item->sort_order }}</span>
            <span class="ml-2 text-xs px-2 py-0.5 bg-gray-100 rounded">{{ $item->question_type }}</span>
            <span class="ml-2 text-gray-700">{{ Str::limit($item->prompt_text, 60) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

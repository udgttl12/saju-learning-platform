@php $t = $track ?? null; @endphp

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

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">코드 *</label>
            <input type="text" name="code" value="{{ old('code', $t?->code) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">슬러그 *</label>
            <input type="text" name="slug" value="{{ old('slug', $t?->slug) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">제목 *</label>
        <input type="text" name="title" value="{{ old('title', $t?->title) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">간단 설명</label>
        <textarea name="short_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('short_description', $t?->short_description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">잠금 규칙 JSON</label>
        <textarea name="unlock_rule_json" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono text-xs">{{ old('unlock_rule_json', $t?->unlock_rule_json ? json_encode($t->unlock_rule_json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) : '') }}</textarea>
        <p class="mt-1 text-xs text-gray-500">예: {"requires":[{"type":"track_exam_passed","code":"TRACK_SAJU_STRUCTURE"}]}</p>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">대상 수강자</label>
            <input type="text" name="target_audience" value="{{ old('target_audience', $t?->target_audience) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">난이도 (1~5) *</label>
            <input type="number" name="difficulty_level" value="{{ old('difficulty_level', $t?->difficulty_level ?? 1) }}" min="1" max="5" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">예상 시간(분)</label>
            <input type="number" name="estimated_total_minutes" value="{{ old('estimated_total_minutes', $t?->estimated_total_minutes) }}" min="0"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">정렬 순서</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $t?->sort_order ?? 0) }}" min="0"
                   class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">공개 상태 *</label>
            <select name="publish_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="draft" {{ old('publish_status', $t?->publish_status ?? 'draft') === 'draft' ? 'selected' : '' }}>임시저장</option>
                <option value="published" {{ old('publish_status', $t?->publish_status) === 'published' ? 'selected' : '' }}>공개</option>
                <option value="archived" {{ old('publish_status', $t?->publish_status) === 'archived' ? 'selected' : '' }}>보관</option>
            </select>
        </div>
    </div>
</div>

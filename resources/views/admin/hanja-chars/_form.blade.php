@php
    $h = $hanjaChar ?? null;
    $categoryOptions = \App\Support\UiLabel::hanjaCategories();
    $selectedCategory = old('category', $h?->category);

    if ($selectedCategory && ! array_key_exists($selectedCategory, $categoryOptions)) {
        $categoryOptions[$selectedCategory] = $selectedCategory;
    }
@endphp

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
            <label class="block text-sm font-medium text-gray-700">한자 *</label>
            <input type="text" name="char_value" value="{{ old('char_value', $h?->char_value) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">슬러그 *</label>
            <input type="text" name="slug" value="{{ old('slug', $h?->slug) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">음 (한글) *</label>
            <input type="text" name="reading_ko" value="{{ old('reading_ko', $h?->reading_ko) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">뜻 (한글) *</label>
            <input type="text" name="meaning_ko" value="{{ old('meaning_ko', $h?->meaning_ko) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">카테고리</label>
            <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">선택</option>
                @foreach($categoryOptions as $value => $label)
                    <option value="{{ $value }}" {{ $selectedCategory === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">오행</label>
            <select name="element" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">선택</option>
                @foreach(['wood', 'fire', 'earth', 'metal', 'water'] as $el)
                    <option value="{{ $el }}" {{ old('element', $h?->element) === $el ? 'selected' : '' }}>
                        {{ ['wood'=>'목(木)', 'fire'=>'화(火)', 'earth'=>'토(土)', 'metal'=>'금(金)', 'water'=>'수(水)'][$el] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">음양</label>
            <select name="yin_yang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">선택</option>
                <option value="yin" {{ old('yin_yang', $h?->yin_yang) === 'yin' ? 'selected' : '' }}>음(陰)</option>
                <option value="yang" {{ old('yin_yang', $h?->yin_yang) === 'yang' ? 'selected' : '' }}>양(陽)</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">획수</label>
        <input type="number" name="stroke_count" value="{{ old('stroke_count', $h?->stroke_count) }}" min="1"
               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">구조 설명</label>
        <textarea name="structure_note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('structure_note', $h?->structure_note) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">연상 텍스트</label>
        <textarea name="mnemonic_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('mnemonic_text', $h?->mnemonic_text) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">사주에서의 활용</label>
        <textarea name="usage_in_saju" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('usage_in_saju', $h?->usage_in_saju) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">공개 상태 *</label>
        <select name="publish_status" required class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm">
            <option value="draft" {{ old('publish_status', $h?->publish_status ?? 'draft') === 'draft' ? 'selected' : '' }}>임시저장</option>
            <option value="published" {{ old('publish_status', $h?->publish_status) === 'published' ? 'selected' : '' }}>공개</option>
            <option value="archived" {{ old('publish_status', $h?->publish_status) === 'archived' ? 'selected' : '' }}>보관</option>
        </select>
    </div>
</div>

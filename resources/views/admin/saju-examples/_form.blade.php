@php $ex = $sajuExample ?? null; @endphp

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
            <input type="text" name="code" value="{{ old('code', $ex?->code) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">슬러그 *</label>
            <input type="text" name="slug" value="{{ old('slug', $ex?->slug) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">제목 *</label>
        <input type="text" name="title" value="{{ old('title', $ex?->title) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">설명</label>
        <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $ex?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">성별</label>
            <select name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">미지정</option>
                <option value="M" {{ old('gender', $ex?->gender) === 'M' ? 'selected' : '' }}>남</option>
                <option value="F" {{ old('gender', $ex?->gender) === 'F' ? 'selected' : '' }}>여</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">양력 생일</label>
            <input type="datetime-local" name="solar_birth_datetime" value="{{ old('solar_birth_datetime', $ex?->solar_birth_datetime?->format('Y-m-d\TH:i')) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">음력 표기</label>
            <input type="text" name="lunar_birth_label" value="{{ old('lunar_birth_label', $ex?->lunar_birth_label) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <fieldset class="border border-gray-200 rounded-md p-4">
        <legend class="text-sm font-medium text-gray-700 px-2">사주 팔자 (천간/지지)</legend>
        <div class="grid grid-cols-4 gap-4 mt-2">
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">연주</div>
                <input type="text" name="year_stem" value="{{ old('year_stem', $ex?->year_stem) }}" maxlength="4" placeholder="천간"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg mb-1">
                <input type="text" name="year_branch" value="{{ old('year_branch', $ex?->year_branch) }}" maxlength="4" placeholder="지지"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg">
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">월주</div>
                <input type="text" name="month_stem" value="{{ old('month_stem', $ex?->month_stem) }}" maxlength="4" placeholder="천간"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg mb-1">
                <input type="text" name="month_branch" value="{{ old('month_branch', $ex?->month_branch) }}" maxlength="4" placeholder="지지"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg">
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">일주</div>
                <input type="text" name="day_stem" value="{{ old('day_stem', $ex?->day_stem) }}" maxlength="4" placeholder="천간"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg mb-1">
                <input type="text" name="day_branch" value="{{ old('day_branch', $ex?->day_branch) }}" maxlength="4" placeholder="지지"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg">
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">시주</div>
                <input type="text" name="hour_stem" value="{{ old('hour_stem', $ex?->hour_stem) }}" maxlength="4" placeholder="천간"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg mb-1">
                <input type="text" name="hour_branch" value="{{ old('hour_branch', $ex?->hour_branch) }}" maxlength="4" placeholder="지지"
                       class="block w-full rounded-md border-gray-300 shadow-sm text-center text-lg">
            </div>
        </div>
    </fieldset>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">난이도 (1~5) *</label>
            <input type="number" name="difficulty_level" value="{{ old('difficulty_level', $ex?->difficulty_level ?? 1) }}" min="1" max="5" required
                   class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">공개 상태 *</label>
            <select name="publish_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="draft" {{ old('publish_status', $ex?->publish_status ?? 'draft') === 'draft' ? 'selected' : '' }}>임시저장</option>
                <option value="published" {{ old('publish_status', $ex?->publish_status) === 'published' ? 'selected' : '' }}>공개</option>
                <option value="archived" {{ old('publish_status', $ex?->publish_status) === 'archived' ? 'selected' : '' }}>보관</option>
            </select>
        </div>
    </div>
</div>

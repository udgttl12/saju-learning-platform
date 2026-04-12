@php $l = $lesson ?? null; @endphp

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
        <label class="block text-sm font-medium text-gray-700">학습 트랙 *</label>
        <select name="learning_track_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">선택</option>
            @foreach($tracks as $track)
                <option value="{{ $track->id }}" {{ old('learning_track_id', $l?->learning_track_id) == $track->id ? 'selected' : '' }}>
                    {{ $track->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">코드 *</label>
            <input type="text" name="code" value="{{ old('code', $l?->code) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">슬러그 *</label>
            <input type="text" name="slug" value="{{ old('slug', $l?->slug) }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">제목 *</label>
        <input type="text" name="title" value="{{ old('title', $l?->title) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">학습 목표</label>
        <textarea name="objective" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('objective', $l?->objective) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">요약</label>
        <textarea name="summary" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('summary', $l?->summary) }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">레슨 유형 *</label>
            <input type="text" name="lesson_type" value="{{ old('lesson_type', $l?->lesson_type ?? 'lecture') }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">난이도 (1~5) *</label>
            <input type="number" name="difficulty_level" value="{{ old('difficulty_level', $l?->difficulty_level ?? 1) }}" min="1" max="5" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">예상 시간(분)</label>
            <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', $l?->estimated_minutes) }}" min="0"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">정렬 순서</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $l?->sort_order ?? 0) }}" min="0"
                   class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">공개 상태 *</label>
            <select name="publish_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="draft" {{ old('publish_status', $l?->publish_status ?? 'draft') === 'draft' ? 'selected' : '' }}>임시저장</option>
                <option value="published" {{ old('publish_status', $l?->publish_status) === 'published' ? 'selected' : '' }}>공개</option>
                <option value="archived" {{ old('publish_status', $l?->publish_status) === 'archived' ? 'selected' : '' }}>보관</option>
            </select>
        </div>
    </div>
</div>

{{-- Lesson Steps --}}
<div class="mt-6 bg-white rounded-lg shadow p-6" x-data="lessonSteps()">
    <h3 class="text-lg font-medium text-gray-900 mb-4">레슨 스텝</h3>

    <template x-for="(step, index) in steps" :key="index">
        <div class="border border-gray-200 rounded-md p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-700" x-text="'스텝 ' + (index + 1)"></span>
                <button type="button" @click="removeStep(index)" class="text-red-500 hover:text-red-700 text-sm">제거</button>
            </div>
            <input type="hidden" :name="'steps[' + index + '][id]'" :value="step.id">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs text-gray-500">유형</label>
                    <input type="text" :name="'steps[' + index + '][step_type]'" x-model="step.step_type"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">제목</label>
                    <input type="text" :name="'steps[' + index + '][title]'" x-model="step.title"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-xs text-gray-500">내용 (마크다운)</label>
                <textarea :name="'steps[' + index + '][content_markdown]'" x-model="step.content_markdown" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"></textarea>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs text-gray-500">정렬순서</label>
                    <input type="number" :name="'steps[' + index + '][sort_order]'" x-model="step.sort_order" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">예상시간(분)</label>
                    <input type="number" :name="'steps[' + index + '][estimated_minutes]'" x-model="step.estimated_minutes" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="hidden" :name="'steps[' + index + '][is_required]'" value="0">
                        <input type="checkbox" :name="'steps[' + index + '][is_required]'" value="1" :checked="step.is_required"
                               class="rounded border-gray-300 text-indigo-600 mr-2">
                        필수 스텝
                    </label>
                </div>
            </div>
        </div>
    </template>

    <button type="button" @click="addStep()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-200">
        + 스텝 추가
    </button>
</div>

<script>
function lessonSteps() {
    return {
        steps: @json($l?->steps ?? []),
        addStep() {
            this.steps.push({
                id: null,
                step_type: 'content',
                title: '',
                content_markdown: '',
                sort_order: this.steps.length,
                is_required: true,
                estimated_minutes: 5,
            });
        },
        removeStep(index) {
            this.steps.splice(index, 1);
        }
    }
}
</script>

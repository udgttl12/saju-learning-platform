<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                한자 카드: {{ $hanja->char_value }}
            </h2>
            <button onclick="history.back()" class="text-sm text-gray-500 hover:text-gray-700">&larr; 돌아가기</button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 메인 한자 카드 --}}
            @php
                $elementColors = [
                    'wood'  => ['bg' => 'bg-green-50',  'border' => 'border-green-300', 'text' => 'text-green-700',  'badge' => 'bg-green-100 text-green-800'],
                    'fire'  => ['bg' => 'bg-red-50',    'border' => 'border-red-300',   'text' => 'text-red-700',    'badge' => 'bg-red-100 text-red-800'],
                    'earth' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300','text' => 'text-yellow-700', 'badge' => 'bg-yellow-100 text-yellow-800'],
                    'metal' => ['bg' => 'bg-gray-50',   'border' => 'border-gray-300',  'text' => 'text-gray-700',   'badge' => 'bg-gray-200 text-gray-800'],
                    'water' => ['bg' => 'bg-blue-50',   'border' => 'border-blue-300',  'text' => 'text-blue-700',   'badge' => 'bg-blue-100 text-blue-800'],
                ];
                $colors = $elementColors[$hanja->element] ?? $elementColors['earth'];
                $elementKo = match($hanja->element) {
                    'wood' => '목(木)',
                    'fire' => '화(火)',
                    'earth' => '토(土)',
                    'metal' => '금(金)',
                    'water' => '수(水)',
                    default => $hanja->element,
                };
                $yinYangKo = match($hanja->yin_yang) {
                    'yin' => '음(陰)',
                    'yang' => '양(陽)',
                    default => $hanja->yin_yang,
                };
            @endphp

            <div class="{{ $colors['bg'] }} {{ $colors['border'] }} border-2 rounded-2xl p-8 text-center">
                {{-- 큰 한자 글자 --}}
                <div class="text-9xl font-serif {{ $colors['text'] }} mb-4 leading-none">{{ $hanja->char_value }}</div>

                {{-- 음독 & 훈독 --}}
                <div class="text-2xl font-semibold text-gray-800 mb-1">{{ $hanja->reading_ko }}</div>
                <div class="text-lg text-gray-600 mb-4">{{ $hanja->meaning_ko }}</div>

                {{-- 오행 & 음양 뱃지 --}}
                <div class="flex items-center justify-center gap-3">
                    @if($hanja->element)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors['badge'] }}">
                            {{ $elementKo }}
                        </span>
                    @endif
                    @if($hanja->yin_yang)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            {{ $yinYangKo }}
                        </span>
                    @endif
                    @if($hanja->stroke_count)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200">
                            {{ $hanja->stroke_count }}획
                        </span>
                    @endif
                </div>

                {{-- 즐겨찾기 버튼 --}}
                @auth
                    <div class="mt-6">
                        <form action="{{ route('bookmarks.toggle') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="target_type" value="hanja_char">
                            <input type="hidden" name="target_id" value="{{ $hanja->id }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                                    {{ $isBookmarked ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                @if($isBookmarked)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                    즐겨찾기 해제
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                    즐겨찾기
                                @endif
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            {{-- 직접 써보기 섹션 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">직접 써보기</h3>
                <p class="text-sm text-gray-600 mb-4">가이드 글자를 따라 직접 써보세요. 마우스, 펜, 터치 모두 지원됩니다.</p>

                <div x-data="{
                    isDrawing: false,
                    strokes: [],
                    currentStroke: [],
                    canvas: null,
                    ctx: null,
                    init() {
                        this.canvas = this.$refs.hanjaCanvas;
                        this.ctx = this.canvas.getContext('2d');
                        this.drawGuide();
                    },
                    drawGuide() {
                        this.ctx.clearRect(0, 0, 400, 400);
                        // 격자 가이드라인
                        this.ctx.strokeStyle = '#e5e7eb';
                        this.ctx.lineWidth = 1;
                        this.ctx.setLineDash([5, 5]);
                        this.ctx.beginPath();
                        this.ctx.moveTo(200, 0); this.ctx.lineTo(200, 400);
                        this.ctx.moveTo(0, 200); this.ctx.lineTo(400, 200);
                        this.ctx.moveTo(0, 0); this.ctx.lineTo(400, 400);
                        this.ctx.moveTo(400, 0); this.ctx.lineTo(0, 400);
                        this.ctx.stroke();
                        this.ctx.setLineDash([]);
                        // 테두리
                        this.ctx.strokeStyle = '#d1d5db';
                        this.ctx.lineWidth = 2;
                        this.ctx.strokeRect(1, 1, 398, 398);
                        // 가이드 글자
                        this.ctx.font = '280px serif';
                        this.ctx.fillStyle = 'rgba(0,0,0,0.06)';
                        this.ctx.textAlign = 'center';
                        this.ctx.textBaseline = 'middle';
                        this.ctx.fillText('{{ $hanja->char_value }}', 200, 210);
                        // 기존 획 복원
                        this.redrawStrokes();
                    },
                    redrawStrokes() {
                        this.ctx.strokeStyle = '#1e293b';
                        this.ctx.lineWidth = 4;
                        this.ctx.lineCap = 'round';
                        this.ctx.lineJoin = 'round';
                        this.strokes.forEach(stroke => {
                            if (stroke.length < 2) return;
                            this.ctx.beginPath();
                            this.ctx.moveTo(stroke[0].x, stroke[0].y);
                            stroke.slice(1).forEach(p => this.ctx.lineTo(p.x, p.y));
                            this.ctx.stroke();
                        });
                    },
                    getPos(e) {
                        const rect = this.canvas.getBoundingClientRect();
                        const scaleX = 400 / rect.width;
                        const scaleY = 400 / rect.height;
                        return { x: (e.clientX - rect.left) * scaleX, y: (e.clientY - rect.top) * scaleY };
                    },
                    startDraw(e) {
                        e.preventDefault();
                        this.isDrawing = true;
                        this.currentStroke = [this.getPos(e)];
                    },
                    draw(e) {
                        if (!this.isDrawing) return;
                        e.preventDefault();
                        const pos = this.getPos(e);
                        this.currentStroke.push(pos);
                        this.ctx.strokeStyle = '#1e293b';
                        this.ctx.lineWidth = 4;
                        this.ctx.lineCap = 'round';
                        this.ctx.lineJoin = 'round';
                        const prev = this.currentStroke[this.currentStroke.length - 2];
                        this.ctx.beginPath();
                        this.ctx.moveTo(prev.x, prev.y);
                        this.ctx.lineTo(pos.x, pos.y);
                        this.ctx.stroke();
                    },
                    endDraw(e) {
                        if (!this.isDrawing) return;
                        e.preventDefault();
                        this.isDrawing = false;
                        if (this.currentStroke.length > 1) {
                            this.strokes.push([...this.currentStroke]);
                        }
                        this.currentStroke = [];
                    },
                    undo() {
                        this.strokes.pop();
                        this.drawGuide();
                    },
                    clearAll() {
                        this.strokes = [];
                        this.drawGuide();
                    }
                }" class="flex flex-col items-center">
                    <div class="border-2 border-gray-300 rounded-xl overflow-hidden mb-4 bg-white touch-none" style="width:100%;max-width:400px;aspect-ratio:1/1;">
                        <canvas x-ref="hanjaCanvas" width="400" height="400"
                            style="width:100%;height:100%;cursor:crosshair;"
                            @pointerdown="startDraw($event)"
                            @pointermove="draw($event)"
                            @pointerup="endDraw($event)"
                            @pointerleave="endDraw($event)">
                        </canvas>
                    </div>
                    <div class="flex gap-3">
                        <button @click="undo()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a4 4 0 014 4v0a4 4 0 01-4 4H3m0 0l4-4m-4 4l4 4"/></svg>
                            다시 쓰기
                        </button>
                        <button @click="clearAll()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            지우기
                        </button>
                    </div>
                </div>
            </div>

            {{-- 상세 정보 카드들 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 기억법 (Mnemonic) --}}
                @if($hanja->mnemonic_text)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">기억법</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $hanja->mnemonic_text }}</p>
                    </div>
                @endif

                {{-- 사주에서의 활용 --}}
                @if($hanja->usage_in_saju)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">사주에서의 활용</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $hanja->usage_in_saju }}</p>
                    </div>
                @endif

                {{-- 구조 설명 --}}
                @if($hanja->structure_note)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">구조 설명</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $hanja->structure_note }}</p>
                    </div>
                @endif

                {{-- 카테고리 --}}
                @if($hanja->category)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">카테고리</h3>
                        <p class="text-sm text-gray-700">{{ $hanja->category }}</p>
                    </div>
                @endif
            </div>

            {{-- 관련 레슨 --}}
            @if($hanja->lessons->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">관련 레슨</h3>
                    <div class="space-y-2">
                        @foreach($hanja->lessons as $lesson)
                            <a href="{{ route('lessons.show', $lesson->slug) }}"
                               class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-indigo-300 transition">
                                <span class="text-sm text-gray-800">{{ $lesson->title }}</span>
                                <span class="text-xs text-gray-400">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 관련 그룹 --}}
            @if($hanja->groups->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">소속 한자 그룹</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($hanja->groups as $group)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                {{ $group->title ?? $group->name ?? $group->code }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

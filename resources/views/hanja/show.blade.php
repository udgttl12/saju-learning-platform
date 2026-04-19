<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                한자 카드: {{ $hanja->char_value }}
            </h2>
            <button onclick="history.back()" class="text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white">&larr; 돌아가기</button>
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
                <div class="text-7xl sm:text-8xl lg:text-9xl font-serif {{ $colors['text'] }} mb-4 leading-none">{{ $hanja->char_value }}</div>

                {{-- 음독 & 훈독 --}}
                <div class="text-2xl font-semibold text-gray-800 dark:text-white mb-1">{{ $hanja->reading_ko }}</div>
                <div class="text-lg text-gray-600 dark:text-slate-300 mb-4">{{ $hanja->meaning_ko }}</div>

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

            {{-- 직접 써보기 (큰 캔버스 1개) --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-4">직접 써보기</h3>
                <p class="text-sm text-gray-600 dark:text-slate-300 mb-4">가이드 글자를 따라 직접 써보세요.</p>

                <div x-data="{
                    isDrawing: false, strokes: [], currentStroke: [], canvas: null, ctx: null,
                    init() { this.canvas = this.$refs.mainCanvas; this.ctx = this.canvas.getContext('2d'); this.drawGuide(); },
                    drawGuide() {
                        const S = 400;
                        this.ctx.clearRect(0, 0, S, S);
                        this.ctx.strokeStyle = '#e5e7eb'; this.ctx.lineWidth = 1; this.ctx.setLineDash([5, 5]);
                        this.ctx.beginPath();
                        this.ctx.moveTo(S/2, 0); this.ctx.lineTo(S/2, S);
                        this.ctx.moveTo(0, S/2); this.ctx.lineTo(S, S/2);
                        this.ctx.moveTo(0, 0); this.ctx.lineTo(S, S);
                        this.ctx.moveTo(S, 0); this.ctx.lineTo(0, S);
                        this.ctx.stroke(); this.ctx.setLineDash([]);
                        this.ctx.strokeStyle = '#d1d5db'; this.ctx.lineWidth = 2; this.ctx.strokeRect(1, 1, S-2, S-2);
                        this.ctx.font = '280px serif'; this.ctx.fillStyle = '#e2e8f0';
                        this.ctx.textAlign = 'center'; this.ctx.textBaseline = 'middle';
                        this.ctx.fillText('{{ $hanja->char_value }}', S/2, S/2 + 10);
                        this.redrawStrokes();
                    },
                    redrawStrokes() {
                        this.ctx.strokeStyle = '#1e293b'; this.ctx.lineWidth = 4; this.ctx.lineCap = 'round'; this.ctx.lineJoin = 'round';
                        this.strokes.forEach(s => { if (s.length < 2) return; this.ctx.beginPath(); this.ctx.moveTo(s[0].x, s[0].y); s.slice(1).forEach(p => this.ctx.lineTo(p.x, p.y)); this.ctx.stroke(); });
                    },
                    getPos(e) { const r = this.canvas.getBoundingClientRect(); return { x: (e.clientX - r.left) * 400 / r.width, y: (e.clientY - r.top) * 400 / r.height }; },
                    startDraw(e) { e.preventDefault(); this.isDrawing = true; this.currentStroke = [this.getPos(e)]; },
                    draw(e) { if (!this.isDrawing) return; e.preventDefault(); const p = this.getPos(e); this.currentStroke.push(p); const prev = this.currentStroke[this.currentStroke.length - 2]; this.ctx.strokeStyle = '#1e293b'; this.ctx.lineWidth = 4; this.ctx.lineCap = 'round'; this.ctx.lineJoin = 'round'; this.ctx.beginPath(); this.ctx.moveTo(prev.x, prev.y); this.ctx.lineTo(p.x, p.y); this.ctx.stroke(); },
                    endDraw(e) { if (!this.isDrawing) return; e.preventDefault(); this.isDrawing = false; if (this.currentStroke.length > 1) this.strokes.push([...this.currentStroke]); this.currentStroke = []; },
                    undo() { this.strokes.pop(); this.drawGuide(); },
                    clearAll() { this.strokes = []; this.drawGuide(); }
                }" class="flex flex-col items-center">
                    <div class="border-2 border-gray-300 dark:border-slate-600 rounded-xl overflow-hidden mb-4 bg-white dark:bg-slate-800 w-full max-w-xs sm:max-w-sm md:max-w-md" style="aspect-ratio:1/1;touch-action:none;">
                        <canvas x-ref="mainCanvas" width="400" height="400"
                            class="w-full h-full"
                            style="touch-action:none; cursor: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22><circle cx=%2212%22 cy=%2212%22 r=%224%22 fill=%22%231e293b%22 opacity=%220.7%22/><circle cx=%2212%22 cy=%2212%22 r=%223%22 fill=%22none%22 stroke=%22white%22 stroke-width=%221%22/></svg>') 12 12, crosshair;"
                            @pointerdown="startDraw($event)"
                            @pointermove="draw($event)"
                            @pointerup="endDraw($event)"
                            @pointerleave="endDraw($event)">
                        </canvas>
                    </div>
                    <div class="flex gap-3">
                        <button @click="undo()" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-md hover:bg-gray-50 dark:hover:bg-slate-700 dark:text-slate-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a4 4 0 014 4v0a4 4 0 01-4 4H3m0 0l4-4m-4 4l4 4"/></svg>
                            다시 쓰기
                        </button>
                        <button @click="clearAll()" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm bg-gray-800 text-white rounded-md hover:bg-gray-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            지우기
                        </button>
                    </div>
                </div>
            </div>

            {{-- 반복 연습 버튼 --}}
            <div class="text-center" x-data="{ showPractice: false }"
                 @keydown.escape.window="showPractice = false; document.body.style.overflow = ''">
                <button @click="showPractice = true; document.body.style.overflow = 'hidden'; $nextTick(() => $dispatch('init-practice'))"
                    class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    반복 연습하기
                </button>

                {{-- 모달 오버레이 --}}
                <div x-show="showPractice" x-cloak
                     class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto"
                     style="display:none;">
                    {{-- 배경 --}}
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showPractice = false; document.body.style.overflow = ''"></div>

                    {{-- 모달 본문 --}}
                    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-3xl mx-4 my-8 z-10"
                         @click.stop
                         x-data="{
                            cellCount: 4,
                            cells: [],
                            ready: false,
                            showGuide: true,
                            initCells() {
                                this.cells = [];
                                for (let i = 0; i < this.cellCount; i++) {
                                    this.cells.push({ strokes: [], currentStroke: [], isDrawing: false });
                                }
                                setTimeout(() => {
                                    this.cells.forEach((_, i) => this.drawCellGuide(i));
                                    this.ready = true;
                                }, 100);
                            },
                            setCellCount(n) {
                                this.cellCount = n;
                                this.initCells();
                            },
                            getCanvas(i) { return document.getElementById('practiceCell' + i); },
                            getCtx(i) { const c = this.getCanvas(i); return c ? c.getContext('2d') : null; },
                            drawCellGuide(i) {
                                const ctx = this.getCtx(i);
                                if (!ctx) return;
                                const S = 300;
                                ctx.clearRect(0, 0, S, S);
                                ctx.strokeStyle = '#e5e7eb'; ctx.lineWidth = 1; ctx.setLineDash([4, 4]);
                                ctx.beginPath();
                                ctx.moveTo(S/2, 0); ctx.lineTo(S/2, S);
                                ctx.moveTo(0, S/2); ctx.lineTo(S, S/2);
                                ctx.moveTo(0, 0); ctx.lineTo(S, S);
                                ctx.moveTo(S, 0); ctx.lineTo(0, S);
                                ctx.stroke(); ctx.setLineDash([]);
                                ctx.strokeStyle = '#d1d5db'; ctx.lineWidth = 2; ctx.strokeRect(1, 1, S-2, S-2);
                                if (this.showGuide) {
                                    ctx.font = '200px serif'; ctx.fillStyle = '#e2e8f0';
                                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                                    ctx.fillText('{{ $hanja->char_value }}', S/2, S/2 + 8);
                                }
                                this.redrawCellStrokes(i);
                            },
                            redrawCellStrokes(i) {
                                const ctx = this.getCtx(i); if (!ctx) return;
                                ctx.strokeStyle = '#1e293b'; ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.lineJoin = 'round';
                                this.cells[i].strokes.forEach(stroke => {
                                    if (stroke.length < 2) return;
                                    ctx.beginPath(); ctx.moveTo(stroke[0].x, stroke[0].y);
                                    stroke.slice(1).forEach(p => ctx.lineTo(p.x, p.y)); ctx.stroke();
                                });
                            },
                            getPos(e, i) {
                                const rect = this.getCanvas(i).getBoundingClientRect();
                                return { x: (e.clientX - rect.left) * 300 / rect.width, y: (e.clientY - rect.top) * 300 / rect.height };
                            },
                            startDraw(e, i) { e.preventDefault(); this.cells[i].isDrawing = true; this.cells[i].currentStroke = [this.getPos(e, i)]; },
                            draw(e, i) {
                                if (!this.cells[i].isDrawing) return; e.preventDefault();
                                const pos = this.getPos(e, i); this.cells[i].currentStroke.push(pos);
                                const ctx = this.getCtx(i);
                                ctx.strokeStyle = '#1e293b'; ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.lineJoin = 'round';
                                const prev = this.cells[i].currentStroke[this.cells[i].currentStroke.length - 2];
                                ctx.beginPath(); ctx.moveTo(prev.x, prev.y); ctx.lineTo(pos.x, pos.y); ctx.stroke();
                            },
                            endDraw(e, i) {
                                if (!this.cells[i].isDrawing) return; e.preventDefault();
                                this.cells[i].isDrawing = false;
                                if (this.cells[i].currentStroke.length > 1) this.cells[i].strokes.push([...this.cells[i].currentStroke]);
                                this.cells[i].currentStroke = [];
                            },
                            undoCell(i) { this.cells[i].strokes.pop(); this.drawCellGuide(i); },
                            clearCell(i) { this.cells[i].strokes = []; this.drawCellGuide(i); },
                            clearAll() { this.cells.forEach((_, i) => this.clearCell(i)); }
                         }"
                         @init-practice.window="initCells()">

                        {{-- 모달 헤더 --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <span class="text-4xl font-serif {{ $colors['text'] }}">{{ $hanja->char_value }}</span>
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-white">{{ $hanja->reading_ko }} · {{ $hanja->meaning_ko }}</div>
                                    <div class="text-xs text-gray-400 dark:text-slate-500">가이드 글자를 따라 반복해서 써보세요</div>
                                </div>
                            </div>
                            <button @click="showPractice = false; document.body.style.overflow = ''"
                                class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- 컨트롤 바 --}}
                        <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-50 dark:border-slate-700 flex-wrap">
                            <span class="text-sm text-gray-600 dark:text-slate-300">반복 칸 수:</span>
                            <div class="flex gap-1">
                                <template x-for="n in [2, 4, 6, 8]" :key="n">
                                    <button @click="setCellCount(n)"
                                        :class="cellCount === n ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="w-9 h-9 rounded-lg text-sm font-semibold transition"
                                        x-text="n"></button>
                                </template>
                            </div>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer select-none ml-3">
                                <input type="checkbox" x-model="showGuide"
                                    @change="cells.forEach((_, i) => drawCellGuide(i))"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="text-sm text-gray-600 dark:text-slate-300">가이드 표시</span>
                            </label>
                            <button @click="clearAll()" class="ml-auto text-sm text-gray-500 dark:text-slate-400 hover:text-red-600 transition">전체 지우기</button>
                        </div>

                        {{-- 노트 그리드 --}}
                        <div class="p-6">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(cell, i) in cells" :key="i">
                                    <div class="relative group">
                                        <span class="absolute top-1 left-2 text-xs text-gray-300 font-mono z-10" x-text="i + 1"></span>
                                        <div class="border-2 border-gray-200 dark:border-slate-700 rounded-lg overflow-hidden bg-white dark:bg-slate-800 aspect-square hover:border-indigo-300 transition" style="touch-action:none;">
                                            <canvas :id="'practiceCell' + i" width="300" height="300"
                                                class="w-full h-full"
                                                style="touch-action:none; cursor: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22><circle cx=%2212%22 cy=%2212%22 r=%224%22 fill=%22%231e293b%22 opacity=%220.7%22/><circle cx=%2212%22 cy=%2212%22 r=%223%22 fill=%22none%22 stroke=%22white%22 stroke-width=%221%22/></svg>') 12 12, crosshair;"
                                                @pointerdown="startDraw($event, i)"
                                                @pointermove="draw($event, i)"
                                                @pointerup="endDraw($event, i)"
                                                @pointerleave="endDraw($event, i)">
                                            </canvas>
                                        </div>
                                        <div class="absolute bottom-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition z-10">
                                            <button @click="undoCell(i)" class="w-7 h-7 bg-white/90 border border-gray-200 rounded text-gray-500 hover:text-indigo-600 flex items-center justify-center" title="되돌리기">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a4 4 0 014 4v0a4 4 0 01-4 4H3"/></svg>
                                            </button>
                                            <button @click="clearCell(i)" class="w-7 h-7 bg-white/90 border border-gray-200 rounded text-gray-500 hover:text-red-600 flex items-center justify-center" title="지우기">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 상세 정보 카드들 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 기억법 (Mnemonic) --}}
                @if($hanja->mnemonic_text)
                    <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-3">기억법</h3>
                        <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed">{{ $hanja->mnemonic_text }}</p>
                    </div>
                @endif

                {{-- 사주에서의 활용 --}}
                @if($hanja->usage_in_saju)
                    <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-3">사주에서의 활용</h3>
                        <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed">{{ $hanja->usage_in_saju }}</p>
                    </div>
                @endif

                {{-- 구조 설명 --}}
                @if($hanja->structure_note)
                    <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-3">구조 설명</h3>
                        <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed">{{ $hanja->structure_note }}</p>
                    </div>
                @endif

                {{-- 카테고리 --}}
                @if($hanja->category)
                    <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-3">카테고리</h3>
                        <p class="text-sm text-gray-700 dark:text-slate-300">{{ \App\Support\UiLabel::hanjaCategory($hanja->category) }}</p>
                    </div>
                @endif
            </div>

            {{-- 관련 레슨 --}}
            @if($hanja->lessons->isNotEmpty())
                <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-4">관련 레슨</h3>
                    <div class="space-y-2">
                        @foreach($hanja->lessons as $lesson)
                            <a href="{{ route('lessons.show', $lesson->slug) }}"
                               class="flex items-center justify-between p-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:border-indigo-300 transition">
                                <span class="text-sm text-gray-800 dark:text-white">{{ $lesson->title }}</span>
                                <span class="text-xs text-gray-400 dark:text-slate-500">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 관련 그룹 --}}
            @if($hanja->groups->isNotEmpty())
                <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-4">소속 한자 그룹</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($hanja->groups as $group)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300">
                                {{ $group->title ?? $group->name ?? $group->code }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

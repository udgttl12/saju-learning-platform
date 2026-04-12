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

            {{-- 연습하기 버튼 --}}
            <div class="text-center" x-data="{ showPractice: false }"
                 @keydown.escape.window="showPractice = false; document.body.style.overflow = ''">
                <button @click="showPractice = true; document.body.style.overflow = 'hidden'; $nextTick(() => $dispatch('init-practice'))"
                    class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    직접 써보기
                </button>

                {{-- 모달 오버레이 --}}
                <div x-show="showPractice" x-cloak
                     class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto"
                     style="display:none;">
                    {{-- 배경 --}}
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showPractice = false; document.body.style.overflow = ''"></div>

                    {{-- 모달 본문 --}}
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 my-8 z-10"
                         @click.stop
                         x-data="{
                            cellCount: 4,
                            cells: [],
                            ready: false,
                            initCells() {
                                this.cells = [];
                                for (let i = 0; i < this.cellCount; i++) {
                                    this.cells.push({ strokes: [], currentStroke: [], isDrawing: false });
                                }
                                this.$nextTick(() => {
                                    this.cells.forEach((_, i) => this.drawCellGuide(i));
                                    this.ready = true;
                                });
                            },
                            setCellCount(n) {
                                this.cellCount = n;
                                this.initCells();
                            },
                            getCanvas(i) { return this.$refs['cell' + i]; },
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
                                ctx.font = '200px serif'; ctx.fillStyle = 'rgba(0,0,0,0.15)';
                                ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                                ctx.fillText('{{ $hanja->char_value }}', S/2, S/2 + 8);
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
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-4">
                                <span class="text-4xl font-serif {{ $colors['text'] }}">{{ $hanja->char_value }}</span>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $hanja->reading_ko }} · {{ $hanja->meaning_ko }}</div>
                                    <div class="text-xs text-gray-400">가이드 글자를 따라 반복해서 써보세요</div>
                                </div>
                            </div>
                            <button @click="showPractice = false; document.body.style.overflow = ''"
                                class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- 칸 수 조절 --}}
                        <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-50">
                            <span class="text-sm text-gray-600">반복 칸 수:</span>
                            <div class="flex gap-1">
                                <template x-for="n in [2, 4, 6, 8]" :key="n">
                                    <button @click="setCellCount(n)"
                                        :class="cellCount === n ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="w-9 h-9 rounded-lg text-sm font-semibold transition"
                                        x-text="n"></button>
                                </template>
                            </div>
                            <button @click="clearAll()" class="ml-auto text-sm text-gray-500 hover:text-red-600 transition">전체 지우기</button>
                        </div>

                        {{-- 노트 그리드 --}}
                        <div class="p-6">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(cell, i) in cells" :key="i">
                                    <div class="relative group">
                                        <span class="absolute top-1 left-2 text-xs text-gray-300 font-mono z-10" x-text="i + 1"></span>
                                        <div class="border-2 border-gray-200 rounded-lg overflow-hidden bg-white aspect-square hover:border-indigo-300 transition" style="touch-action:none;">
                                            <canvas :x-ref="'cell' + i" width="300" height="300"
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

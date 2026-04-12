<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 dark:text-slate-500 mb-1">
                    <a href="{{ route('tracks.show', $lesson->learningTrack->slug) }}" class="hover:text-indigo-600">
                        {{ $lesson->learningTrack->title }}
                    </a>
                </p>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $lesson->title }}
                </h2>
            </div>
            <a href="{{ route('tracks.show', $lesson->learningTrack->slug) }}" class="text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white">&larr; 트랙으로</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 레슨 목표 --}}
            @if($lesson->objective)
                <div class="bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 rounded-lg p-4">
                    <div class="text-xs font-medium text-indigo-500 dark:text-indigo-400 mb-1">학습 목표</div>
                    <p class="text-sm text-indigo-800 dark:text-indigo-300">{{ $lesson->objective }}</p>
                </div>
            @endif

            {{-- Step 기반 레슨 플레이어 --}}
            <div x-data="{ currentStep: 0, totalSteps: {{ $lesson->steps->count() }} }" class="space-y-4">

                {{-- 진행 바 --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-4">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-slate-400 mb-2">
                        <span>진행률</span>
                        <span x-text="Math.round(((currentStep + 1) / totalSteps) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                             :style="'width: ' + ((currentStep + 1) / totalSteps * 100) + '%'"></div>
                    </div>
                </div>

                {{-- Step 컨텐츠 --}}
                @foreach($lesson->steps as $stepIndex => $step)
                    <div x-show="currentStep === {{ $stepIndex }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm dark:shadow-slate-900/50 sm:rounded-lg">

                        {{-- Step 헤더 --}}
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                @php
                                    $stepTypeLabel = match($step->step_type) {
                                        'intro' => '도입',
                                        'explanation' => '설명',
                                        'stroke_order' => '필순 학습',
                                        'guided_practice' => '연습',
                                        'quiz' => '퀴즈',
                                        'summary' => '정리',
                                        default => $step->step_type,
                                    };
                                    $stepTypeColor = match($step->step_type) {
                                        'intro' => 'bg-blue-100 text-blue-700',
                                        'explanation' => 'bg-purple-100 text-purple-700',
                                        'stroke_order' => 'bg-amber-100 text-amber-700',
                                        'guided_practice' => 'bg-emerald-100 text-emerald-700',
                                        'quiz' => 'bg-red-100 text-red-700',
                                        'summary' => 'bg-gray-100 text-gray-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stepTypeColor }}">
                                    {{ $stepTypeLabel }}
                                </span>
                                @if($step->title)
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">{{ $step->title }}</h3>
                                @endif
                            </div>
                        </div>

                        {{-- Step 본문 --}}
                        <div class="px-4 py-4 sm:px-6 sm:py-6">
                            @switch($step->step_type)
                                @case('intro')
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                                    {{-- focus 배열이 있으면 강조 표시 --}}
                                    @if($step->payload_json && isset($step->payload_json['focus']))
                                        <div class="mt-6 flex flex-wrap justify-center gap-4">
                                            @foreach($step->payload_json['focus'] as $char)
                                                <div class="w-20 h-20 flex items-center justify-center bg-indigo-50 border-2 border-indigo-300 rounded-xl text-4xl font-serif text-indigo-800 shadow-sm">
                                                    {{ $char }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('explanation')
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>

                                    {{-- examples 배열이 있으면 큰 글자로 표시 --}}
                                    @if($step->payload_json && isset($step->payload_json['examples']))
                                        <div class="mt-6 flex flex-wrap justify-center gap-6">
                                            @foreach($step->payload_json['examples'] as $ex)
                                                <div class="flex flex-col items-center">
                                                    <div class="text-6xl font-serif text-gray-800 mb-2">{{ $ex }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- display_mode별 한자 표시 --}}
                                    @if($step->payload_json && isset($step->payload_json['display_mode']))
                                        @php $displayMode = $step->payload_json['display_mode']; @endphp

                                        @if($displayMode === 'card_grid' && $hanjaChars->isNotEmpty())
                                            <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                                                @foreach($hanjaChars as $hc)
                                                    <a href="{{ route('hanja.show', $hc->slug) }}"
                                                       class="block p-4 border border-gray-200 dark:border-slate-700 rounded-lg text-center hover:border-indigo-300 hover:shadow-md transition">
                                                        <div class="text-4xl font-serif mb-2 dark:text-white">{{ $hc->char_value }}</div>
                                                        <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $hc->reading_ko }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $hc->meaning_ko }}</div>
                                                        @if($hc->element)
                                                            @php
                                                                $elBadge = match($hc->element) {
                                                                    'wood' => 'bg-green-100 text-green-700',
                                                                    'fire' => 'bg-red-100 text-red-700',
                                                                    'earth' => 'bg-yellow-100 text-yellow-700',
                                                                    'metal' => 'bg-gray-200 text-gray-700',
                                                                    'water' => 'bg-blue-100 text-blue-700',
                                                                    default => 'bg-gray-100 text-gray-600',
                                                                };
                                                            @endphp
                                                            <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $elBadge }}">
                                                                {{ $hc->element }}
                                                            </span>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>

                                        @elseif($displayMode === 'sequence' && $hanjaChars->isNotEmpty())
                                            <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
                                                @foreach($hanjaChars as $idx => $hc)
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('hanja.show', $hc->slug) }}"
                                                           class="flex flex-col items-center p-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:border-indigo-300 transition min-w-[80px]">
                                                            <div class="text-3xl font-serif mb-1 dark:text-white">{{ $hc->char_value }}</div>
                                                            <div class="text-xs text-gray-600 dark:text-slate-300">{{ $hc->reading_ko }}</div>
                                                        </a>
                                                        @if(!$loop->last)
                                                            <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($displayMode === 'grid' && $hanjaChars->isNotEmpty())
                                            <div class="mt-6 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                                @foreach($hanjaChars as $hc)
                                                    <a href="{{ route('hanja.show', $hc->slug) }}"
                                                       class="block p-4 border border-gray-200 dark:border-slate-700 rounded-xl text-center hover:border-indigo-300 hover:shadow-md transition">
                                                        <div class="text-5xl font-serif mb-2 dark:text-white">{{ $hc->char_value }}</div>
                                                        <div class="text-sm font-semibold text-gray-800 dark:text-white">{{ $hc->reading_ko }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $hc->meaning_ko }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif

                                    {{-- 기존 hanja_chars slug 기반 (하위 호환) --}}
                                    @if($step->payload_json && isset($step->payload_json['hanja_chars']))
                                        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                                            @foreach($step->payload_json['hanja_chars'] as $charSlug)
                                                @php
                                                    $hc = $lesson->hanjaChars->firstWhere('slug', $charSlug);
                                                @endphp
                                                @if($hc)
                                                    <a href="{{ route('hanja.show', $hc->slug) }}"
                                                       class="block p-4 border border-gray-200 dark:border-slate-700 rounded-lg text-center hover:border-indigo-300 transition">
                                                        <div class="text-4xl mb-2 dark:text-white">{{ $hc->char_value }}</div>
                                                        <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $hc->reading_ko }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $hc->meaning_ko }}</div>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('stroke_order')
                                    <div class="text-center py-6">
                                        {{-- 해당 레슨의 한자들을 큰 글자로 표시 --}}
                                        @if($hanjaChars->isNotEmpty())
                                            <div class="flex flex-wrap justify-center gap-8 mb-6">
                                                @foreach($hanjaChars as $hc)
                                                    <div class="flex flex-col items-center">
                                                        <div class="text-8xl font-serif text-gray-800 dark:text-white leading-none mb-2">{{ $hc->char_value }}</div>
                                                        <div class="text-sm font-medium text-gray-700 dark:text-slate-300">{{ $hc->reading_ko }} ({{ $hc->meaning_ko }})</div>
                                                        @if($hc->stroke_count)
                                                            <div class="text-xs text-gray-400 dark:text-slate-500 mt-1">{{ $hc->stroke_count }}획</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($step->payload_json && isset($step->payload_json['char_value']))
                                            <div class="text-8xl mb-4 font-serif">{{ $step->payload_json['char_value'] }}</div>
                                            @if(isset($step->payload_json['stroke_count']))
                                                <p class="text-sm text-gray-500 mb-2">총 {{ $step->payload_json['stroke_count'] }}획</p>
                                            @endif
                                        @endif

                                        {{-- 획순 안내 --}}
                                        <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-lg p-4 text-left mt-4">
                                            <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-400 mb-2">획순 안내</h4>
                                            <ul class="text-sm text-amber-700 dark:text-amber-300 space-y-1">
                                                <li>1. 위에서 아래로 (從上到下)</li>
                                                <li>2. 왼쪽에서 오른쪽으로 (從左到右)</li>
                                                <li>3. 가로획 먼저, 세로획 나중에</li>
                                                <li>4. 바깥에서 안으로</li>
                                            </ul>
                                        </div>

                                        <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300 text-left mt-4">
                                            {!! nl2br(e($step->content_markdown)) !!}
                                        </div>
                                    </div>
                                    @break

                                @case('guided_practice')
                                    @php
                                        $repeatCount = $step->payload_json['repeat_count'] ?? 3;
                                        $practiceChar = $hanjaChars->first();
                                    @endphp
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300 mb-4">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>

                                    {{-- 연습 안내 --}}
                                    <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-lg p-4 mb-6">
                                        <p class="text-sm text-emerald-800 dark:text-emerald-400 font-medium">
                                            아래 캔버스에서 {{ $repeatCount }}번 연습해보세요!
                                            @if($practiceChar)
                                                가이드 글자: <span class="text-2xl font-serif">{{ $practiceChar->char_value }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    {{-- 쓰기 캔버스 (노트 그리드 방식) --}}
                                    @if($practiceChar)
                                        <div x-data="{
                                            cellCount: {{ $repeatCount * 2 }},
                                            cells: [],
                                            showGuide: true,
                                            practiceCount: 0,
                                            targetCount: {{ $repeatCount }},
                                            init() { this.initCells(); },
                                            initCells() {
                                                this.cells = [];
                                                for (let i = 0; i < this.cellCount; i++) {
                                                    this.cells.push({ strokes: [], currentStroke: [], isDrawing: false });
                                                }
                                                setTimeout(() => { this.cells.forEach((_, i) => this.drawCellGuide(i)); }, 100);
                                            },
                                            getCanvas(i) { return document.getElementById('lessonCell' + i); },
                                            getCtx(i) { const c = this.getCanvas(i); return c ? c.getContext('2d') : null; },
                                            drawCellGuide(i) {
                                                const ctx = this.getCtx(i); if (!ctx) return;
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
                                                    ctx.fillText('{{ $practiceChar->char_value }}', S/2, S/2 + 8);
                                                }
                                                this.redrawCellStrokes(i);
                                            },
                                            redrawCellStrokes(i) {
                                                const ctx = this.getCtx(i); if (!ctx) return;
                                                ctx.strokeStyle = '#1e293b'; ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.lineJoin = 'round';
                                                this.cells[i].strokes.forEach(s => { if (s.length < 2) return; ctx.beginPath(); ctx.moveTo(s[0].x, s[0].y); s.slice(1).forEach(p => ctx.lineTo(p.x, p.y)); ctx.stroke(); });
                                            },
                                            getPos(e, i) { const r = this.getCanvas(i).getBoundingClientRect(); return { x: (e.clientX - r.left) * 300 / r.width, y: (e.clientY - r.top) * 300 / r.height }; },
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
                                        }">
                                            {{-- 컨트롤 --}}
                                            <div class="flex items-center gap-3 mb-4 flex-wrap">
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                                                    <input type="checkbox" x-model="showGuide" @change="cells.forEach((_, i) => drawCellGuide(i))"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    <span class="text-sm text-gray-600">가이드 표시</span>
                                                </label>
                                                <button @click="clearAll()" class="ml-auto text-sm text-gray-500 hover:text-red-600 transition">전체 지우기</button>
                                            </div>
                                            {{-- 노트 그리드 --}}
                                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                                <template x-for="(cell, i) in cells" :key="i">
                                                    <div class="relative group">
                                                        <span class="absolute top-1 left-2 text-xs text-gray-300 font-mono z-10" x-text="i + 1"></span>
                                                        <div class="border-2 border-gray-200 dark:border-slate-700 rounded-lg overflow-hidden bg-white dark:bg-slate-800 aspect-square hover:border-indigo-300 transition" style="touch-action:none;">
                                                            <canvas :id="'lessonCell' + i" width="300" height="300"
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
                                    @endif

                                    @if($step->payload_json && isset($step->payload_json['practice_items']))
                                        <div class="space-y-3 mt-4">
                                            @foreach($step->payload_json['practice_items'] as $item)
                                                <div class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                                                    <p class="text-sm text-gray-700">{{ $item['prompt'] ?? $item }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('quiz')
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300 mb-4">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>

                                    {{-- quiz_set_code로 실제 퀴즈 문항 인라인 표시 --}}
                                    @if($step->payload_json && isset($step->payload_json['quiz_set_code']))
                                        @php $quizSetCode = $step->payload_json['quiz_set_code']; @endphp
                                        @if(isset($quizSets[$quizSetCode]))
                                            @php $quizSet = $quizSets[$quizSetCode]; @endphp
                                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                                <h4 class="text-sm font-semibold text-red-800">{{ $quizSet->title }}</h4>
                                                @if($quizSet->description)
                                                    <p class="text-xs text-red-600 mt-1">{{ $quizSet->description }}</p>
                                                @endif
                                                <p class="text-xs text-red-500 mt-1">합격 점수: {{ $quizSet->pass_score }}점 | 문항 수: {{ $quizSet->items->count() }}개</p>
                                            </div>

                                            <div x-data="{
                                                answers: {},
                                                revealed: {},
                                                score: 0,
                                                totalItems: {{ $quizSet->items->count() }},
                                                answeredCount: 0,
                                                get allAnswered() { return this.answeredCount >= this.totalItems; },
                                                selectChoice(qi, ci, correctIdx) {
                                                    if (this.revealed[qi]) return;
                                                    this.answers[qi] = ci;
                                                    this.revealed[qi] = true;
                                                    this.answeredCount++;
                                                    if (ci === correctIdx) this.score += {{ $quizSet->items->first()?->points ?? 10 }};
                                                },
                                                selectBool(qi, val, correctVal) {
                                                    if (this.revealed[qi]) return;
                                                    this.answers[qi] = val;
                                                    this.revealed[qi] = true;
                                                    this.answeredCount++;
                                                    if (val === correctVal) this.score += {{ $quizSet->items->first()?->points ?? 10 }};
                                                }
                                            }" class="space-y-6">
                                                @foreach($quizSet->items as $qi => $item)
                                                    <div class="p-5 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-700">
                                                        <p class="text-sm font-medium text-gray-800 dark:text-white mb-3">
                                                            <span class="inline-flex items-center justify-center w-6 h-6 bg-red-100 text-red-700 rounded-full text-xs font-bold mr-2">{{ $qi + 1 }}</span>
                                                            {{ $item->prompt_text }}
                                                        </p>

                                                        @if($item->hint_text)
                                                            <p class="text-xs text-gray-400 mb-3 italic" x-show="!revealed[{{ $qi }}]">
                                                                힌트: {{ $item->hint_text }}
                                                            </p>
                                                        @endif

                                                        @if($item->question_type === 'multiple_choice' && $item->choices_json)
                                                            <div class="space-y-2">
                                                                @foreach($item->choices_json as $ci => $choice)
                                                                    <button
                                                                        @click="selectChoice({{ $qi }}, {{ $ci }}, {{ $item->answer_payload_json['correct_choice_index'] }})"
                                                                        :class="{
                                                                            'border-emerald-500 bg-emerald-50 text-emerald-800': revealed[{{ $qi }}] && {{ $ci }} === {{ $item->answer_payload_json['correct_choice_index'] }},
                                                                            'border-red-400 bg-red-50 text-red-800': revealed[{{ $qi }}] && answers[{{ $qi }}] === {{ $ci }} && {{ $ci }} !== {{ $item->answer_payload_json['correct_choice_index'] }},
                                                                            'hover:border-indigo-300 hover:bg-indigo-50': !revealed[{{ $qi }}],
                                                                            'cursor-not-allowed opacity-60': revealed[{{ $qi }}] && answers[{{ $qi }}] !== {{ $ci }} && {{ $ci }} !== {{ $item->answer_payload_json['correct_choice_index'] }},
                                                                        }"
                                                                        :disabled="revealed[{{ $qi }}]"
                                                                        class="w-full text-left px-4 py-2.5 border border-gray-200 rounded-md text-sm transition">
                                                                        <span class="font-medium mr-2">{{ chr(65 + $ci) }}.</span> {{ $choice }}
                                                                        <span x-show="revealed[{{ $qi }}] && {{ $ci }} === {{ $item->answer_payload_json['correct_choice_index'] }}" class="float-right text-emerald-600 font-bold">&#10003;</span>
                                                                        <span x-show="revealed[{{ $qi }}] && answers[{{ $qi }}] === {{ $ci }} && {{ $ci }} !== {{ $item->answer_payload_json['correct_choice_index'] }}" class="float-right text-red-600 font-bold">&#10007;</span>
                                                                    </button>
                                                                @endforeach
                                                            </div>

                                                        @elseif($item->question_type === 'true_false')
                                                            <div class="flex gap-3">
                                                                @foreach([['val' => 'true', 'label' => 'O (맞다)'], ['val' => 'false', 'label' => 'X (틀리다)']] as $opt)
                                                                    @php $correctBool = $item->answer_payload_json['correct_boolean'] ? 'true' : 'false'; @endphp
                                                                    <button
                                                                        @click="selectBool({{ $qi }}, '{{ $opt['val'] }}', '{{ $correctBool }}')"
                                                                        :class="{
                                                                            'border-emerald-500 bg-emerald-50 text-emerald-800': revealed[{{ $qi }}] && '{{ $opt['val'] }}' === '{{ $correctBool }}',
                                                                            'border-red-400 bg-red-50 text-red-800': revealed[{{ $qi }}] && answers[{{ $qi }}] === '{{ $opt['val'] }}' && '{{ $opt['val'] }}' !== '{{ $correctBool }}',
                                                                            'hover:border-indigo-300': !revealed[{{ $qi }}],
                                                                        }"
                                                                        :disabled="revealed[{{ $qi }}]"
                                                                        class="flex-1 px-4 py-3 border border-gray-200 rounded-md text-sm font-medium text-center transition">
                                                                        {{ $opt['label'] }}
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        {{-- 해설 표시 --}}
                                                        @if($item->explanation_text)
                                                            <div x-show="revealed[{{ $qi }}]" x-transition class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                                                <p class="text-xs font-semibold text-blue-700 mb-1">해설</p>
                                                                <p class="text-sm text-blue-800">{{ $item->explanation_text }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                {{-- 결과 요약 --}}
                                                <div x-show="allAnswered" x-transition class="p-5 rounded-lg border-2 text-center"
                                                     :class="score >= {{ $quizSet->pass_score }} ? 'bg-emerald-50 border-emerald-300' : 'bg-amber-50 border-amber-300'">
                                                    <p class="text-lg font-bold mb-1" :class="score >= {{ $quizSet->pass_score }} ? 'text-emerald-800' : 'text-amber-800'">
                                                        <span x-show="score >= {{ $quizSet->pass_score }}">합격!</span>
                                                        <span x-show="score < {{ $quizSet->pass_score }}">아쉬워요, 다시 도전해보세요!</span>
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        점수: <span class="font-semibold" x-text="score"></span> / {{ $quizSet->items->sum('points') }}점
                                                        (합격 기준: {{ $quizSet->pass_score }}점)
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    {{-- 기존 questions 기반 (하위 호환) --}}
                                    @if($step->payload_json && isset($step->payload_json['questions']))
                                        <div x-data="{ answers: {}, revealed: {} }" class="space-y-6 mt-4">
                                            @foreach($step->payload_json['questions'] as $qi => $question)
                                                <div class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                                                    <p class="text-sm font-medium text-gray-800 mb-3">{{ $qi + 1 }}. {{ $question['question'] ?? '' }}</p>
                                                    @if(isset($question['options']))
                                                        <div class="space-y-2">
                                                            @foreach($question['options'] as $oi => $option)
                                                                <button
                                                                    @click="answers[{{ $qi }}] = {{ $oi }}; revealed[{{ $qi }}] = true"
                                                                    :class="{
                                                                        'border-emerald-500 bg-emerald-50': revealed[{{ $qi }}] && {{ $oi }} === {{ $question['answer'] ?? 0 }},
                                                                        'border-red-300 bg-red-50': revealed[{{ $qi }}] && answers[{{ $qi }}] === {{ $oi }} && {{ $oi }} !== {{ $question['answer'] ?? 0 }},
                                                                    }"
                                                                    class="w-full text-left px-4 py-2.5 border border-gray-200 rounded-md text-sm hover:border-indigo-300 transition">
                                                                    {{ $option }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('summary')
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>

                                    {{-- 키워드 표시 --}}
                                    @if($step->payload_json && isset($step->payload_json['keywords']))
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($step->payload_json['keywords'] as $kw)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-600">
                                                    {{ $kw }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- 관련 한자 카드 목록 --}}
                                    @if($lesson->hanjaChars->isNotEmpty())
                                        <div class="mt-6">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">이번 레슨에서 배운 한자</h4>
                                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                                @foreach($lesson->hanjaChars as $hc)
                                                    <a href="{{ route('hanja.show', $hc->slug) }}"
                                                       class="block p-3 border border-gray-200 dark:border-slate-700 rounded-lg text-center hover:border-indigo-300 transition">
                                                        <div class="text-2xl mb-1 dark:text-white">{{ $hc->char_value }}</div>
                                                        <div class="text-xs text-gray-600 dark:text-slate-300">{{ $hc->reading_ko }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @break

                                @default
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                            @endswitch
                        </div>
                    </div>
                @endforeach

                {{-- 네비게이션 버튼 --}}
                <div class="flex items-center justify-between bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-900/50 sm:rounded-lg p-4">
                    <button @click="currentStep = Math.max(0, currentStep - 1)"
                            x-show="currentStep > 0"
                            class="inline-flex items-center px-3 py-2 sm:px-4 border border-gray-300 dark:border-slate-700 text-sm font-medium rounded-md text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        &larr; 이전
                    </button>
                    <div x-show="currentStep === 0"></div>

                    <span class="text-sm text-gray-400 dark:text-slate-500" x-text="(currentStep + 1) + ' / ' + totalSteps"></span>

                    <button @click="currentStep = Math.min(totalSteps - 1, currentStep + 1)"
                            x-show="currentStep < totalSteps - 1"
                            class="inline-flex items-center px-3 py-2 sm:px-4 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                        다음 &rarr;
                    </button>

                    {{-- 마지막 스텝에서 완료 버튼 --}}
                    <div x-show="currentStep === totalSteps - 1">
                        @if($attempt->status !== 'completed')
                            <form action="{{ route('lessons.complete', $lesson->slug) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition">
                                    레슨 완료!
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-md">
                                이미 완료됨
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

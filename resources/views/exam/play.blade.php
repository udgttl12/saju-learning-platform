<x-app-layout>
    <div class="bg-slate-900 min-h-screen pb-16">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 pt-8">

            <div x-data="{
                current: 0,
                total: {{ count($questions) }},
                answers: {},
                submitted: {},
                correctCount: 0,
                questions: @js($questions),
                select(qi, choiceId) {
                    if (this.submitted[qi]) return;
                    this.answers[qi] = choiceId;
                    this.submitted[qi] = true;
                    if (choiceId === this.questions[qi].correct_id) this.correctCount++;
                },
                isCorrectChoice(qi, choiceId) {
                    return this.submitted[qi] && choiceId === this.questions[qi].correct_id;
                },
                isWrongChoice(qi, choiceId) {
                    return this.submitted[qi] && this.answers[qi] === choiceId && choiceId !== this.questions[qi].correct_id;
                },
                next() {
                    if (this.current < this.total - 1) this.current++;
                },
                prev() {
                    if (this.current > 0) this.current--;
                },
                allAnswered() {
                    return Object.keys(this.submitted).length >= this.total;
                }
            }">
                {{-- 헤더 --}}
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-amber-400">{{ $categoryLabel }} 시험</span>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-emerald-400 font-semibold"><span x-text="correctCount"></span>맞음</span>
                            <span class="text-red-400 font-semibold"><span x-text="Object.keys(submitted).length - correctCount"></span>틀림</span>
                            <span class="text-slate-500"><span x-text="Object.keys(submitted).length"></span>/<span x-text="total"></span></span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-3 flex overflow-hidden">
                        <div class="bg-emerald-500 h-3 transition-all duration-300"
                             :style="'width:' + (correctCount / total * 100) + '%'"></div>
                        <div class="bg-red-400 h-3 transition-all duration-300"
                             :style="'width:' + ((Object.keys(submitted).length - correctCount) / total * 100) + '%'"></div>
                    </div>
                </div>

                {{-- 문제 카드 --}}
                <template x-for="(q, qi) in questions" :key="qi">
                    <div x-show="current === qi" class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden">
                        {{-- 문제 번호 + 한자 --}}
                        <div class="p-8 text-center border-b border-white/10">
                            <span class="text-xs text-slate-500" x-text="'Q' + (qi + 1)"></span>
                            <div class="text-6xl sm:text-8xl md:text-9xl font-serif text-white my-6" x-text="q.char_value"></div>
                            <p class="text-slate-400">이 한자의 뜻은?</p>
                        </div>

                        {{-- 선택지 --}}
                        <div class="p-6 space-y-3">
                            <template x-for="(choice, ci) in q.choices" :key="ci">
                                <button @click="select(qi, choice.id)"
                                    :disabled="submitted[qi]"
                                    :class="{
                                        'border-emerald-500 bg-emerald-500/20 text-emerald-400 ring-2 ring-emerald-500/30': isCorrectChoice(qi, choice.id),
                                        'border-red-500 bg-red-500/20 text-red-400 ring-2 ring-red-500/30': isWrongChoice(qi, choice.id),
                                        'border-white/10 hover:border-amber-400/50 hover:bg-white/10 cursor-pointer text-slate-200': !submitted[qi],
                                        'border-white/5 text-slate-600 opacity-50': submitted[qi] && !isCorrectChoice(qi, choice.id) && !isWrongChoice(qi, choice.id),
                                    }"
                                    class="w-full text-left px-4 py-3 sm:px-5 sm:py-4 border-2 rounded-xl transition-all duration-150 flex items-center gap-3 bg-white/5">
                                    <span class="flex-shrink-0 w-9 h-9 rounded-full border-2 flex items-center justify-center text-sm font-bold transition-all"
                                        :class="{
                                            'border-emerald-500 bg-emerald-500 text-white': isCorrectChoice(qi, choice.id),
                                            'border-red-500 bg-red-500 text-white': isWrongChoice(qi, choice.id),
                                            'border-slate-600 text-slate-400': !isCorrectChoice(qi, choice.id) && !isWrongChoice(qi, choice.id),
                                        }"
                                        x-text="['A','B','C','D'][ci]"></span>
                                    <span class="flex-1 font-medium" x-text="choice.text"></span>
                                    <template x-if="isCorrectChoice(qi, choice.id)">
                                        <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </template>
                                    <template x-if="isWrongChoice(qi, choice.id)">
                                        <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </template>
                                </button>
                            </template>
                        </div>

                        {{-- 정답 해설 --}}
                        <div x-show="submitted[qi] && !isCorrectChoice(qi, answers[qi])" x-cloak
                            class="mx-6 mb-6 p-4 bg-amber-500/10 border border-amber-500/30 rounded-xl">
                            <p class="text-sm text-amber-400">
                                <strong x-text="q.char_value"></strong>의 뜻은
                                <strong x-text="q.meaning_ko + ' (' + q.reading_ko + ')'"></strong>입니다.
                            </p>
                        </div>
                    </div>
                </template>

                {{-- 네비게이션 --}}
                <div class="flex items-center justify-between mt-4">
                    <button @click="prev()" x-show="current > 0"
                        class="px-5 py-2.5 border border-white/10 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-slate-200 transition">
                        &larr; 이전
                    </button>
                    <div x-show="current === 0"></div>

                    {{-- 문제 번호 칸 --}}
                    <div class="grid grid-cols-5 sm:grid-cols-10 gap-1 justify-center">
                        <template x-for="(q, qi) in questions" :key="'dot'+qi">
                            <button @click="current = qi"
                                class="w-8 h-8 rounded-lg text-xs font-bold transition-all flex items-center justify-center"
                                :class="{
                                    'bg-emerald-500 text-white': submitted[qi] && answers[qi] === q.correct_id,
                                    'bg-red-500 text-white': submitted[qi] && answers[qi] !== q.correct_id,
                                    'bg-amber-500 text-white ring-2 ring-amber-400/50 scale-110': current === qi && !submitted[qi],
                                    'bg-slate-700 text-slate-400 hover:bg-slate-600': current !== qi && !submitted[qi],
                                }"
                                x-text="qi + 1"></button>
                        </template>
                    </div>

                    <template x-if="current < total - 1">
                        <button @click="next()"
                            class="px-5 py-2.5 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 rounded-xl text-sm font-bold hover:from-amber-500 hover:to-yellow-600 transition-all">
                            다음 &rarr;
                        </button>
                    </template>

                    <template x-if="current === total - 1 && allAnswered()">
                        <form method="POST" action="{{ route('exam.submit') }}">
                            @csrf
                            <template x-for="(q, qi) in questions" :key="'ans'+qi">
                                <input type="hidden" :name="'answers['+qi+']'" :value="answers[qi] || 0">
                            </template>
                            <button type="submit"
                                class="px-6 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                                결과 보기
                            </button>
                        </form>
                    </template>
                </div>

                {{-- 전체 제출 (모바일용) --}}
                <div x-show="allAnswered()" x-cloak class="mt-6 text-center">
                    <form method="POST" action="{{ route('exam.submit') }}">
                        @csrf
                        <template x-for="(q, qi) in questions" :key="'sub'+qi">
                            <input type="hidden" :name="'answers['+qi+']'" :value="answers[qi] || 0">
                        </template>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 rounded-xl text-lg font-bold hover:from-amber-500 hover:to-yellow-600 transition-all shadow-lg shadow-amber-500/25">
                            시험 결과 확인 (<span x-text="correctCount"></span>/<span x-text="total"></span>)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

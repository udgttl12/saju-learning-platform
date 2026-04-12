<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

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
                <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-indigo-600">{{ $categoryLabel }} 시험</span>
                        <span class="text-sm text-gray-500">
                            <span x-text="Object.keys(submitted).length"></span> / <span x-text="total"></span> 완료
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                             :style="'width:' + (Object.keys(submitted).length / total * 100) + '%'"></div>
                    </div>
                </div>

                {{-- 문제 카드 --}}
                <template x-for="(q, qi) in questions" :key="qi">
                    <div x-show="current === qi" class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        {{-- 문제 번호 + 한자 --}}
                        <div class="p-8 text-center border-b border-gray-100">
                            <span class="text-xs text-gray-400" x-text="'Q' + (qi + 1)"></span>
                            <div class="text-8xl sm:text-9xl font-serif text-gray-800 my-6" x-text="q.char_value"></div>
                            <p class="text-gray-500">이 한자의 뜻은?</p>
                        </div>

                        {{-- 선택지 --}}
                        <div class="p-6 space-y-3">
                            <template x-for="(choice, ci) in q.choices" :key="ci">
                                <button @click="select(qi, choice.id)"
                                    :disabled="submitted[qi]"
                                    :class="{
                                        'border-emerald-500 bg-emerald-50 text-emerald-800': isCorrectChoice(qi, choice.id),
                                        'border-red-400 bg-red-50 text-red-800': isWrongChoice(qi, choice.id),
                                        'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50': !submitted[qi],
                                        'border-gray-100 text-gray-400': submitted[qi] && !isCorrectChoice(qi, choice.id) && !isWrongChoice(qi, choice.id),
                                    }"
                                    class="w-full text-left px-5 py-4 border-2 rounded-xl transition flex items-center gap-3">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-bold"
                                        :class="{
                                            'border-emerald-500 bg-emerald-500 text-white': isCorrectChoice(qi, choice.id),
                                            'border-red-400 bg-red-400 text-white': isWrongChoice(qi, choice.id),
                                            'border-gray-300 text-gray-500': !isCorrectChoice(qi, choice.id) && !isWrongChoice(qi, choice.id),
                                        }"
                                        x-text="['A','B','C','D'][ci]"></span>
                                    <span class="text-sm font-medium" x-text="choice.text"></span>
                                    <template x-if="isCorrectChoice(qi, choice.id)">
                                        <svg class="w-5 h-5 ml-auto text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </template>
                                    <template x-if="isWrongChoice(qi, choice.id)">
                                        <svg class="w-5 h-5 ml-auto text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </template>
                                </button>
                            </template>
                        </div>

                        {{-- 정답 해설 --}}
                        <div x-show="submitted[qi] && !isCorrectChoice(qi, answers[qi])" x-cloak
                            class="mx-6 mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-sm text-amber-800">
                                <strong x-text="q.char_value"></strong>의 뜻은
                                <strong x-text="q.meaning_ko + ' (' + q.reading_ko + ')'"></strong>입니다.
                            </p>
                        </div>
                    </div>
                </template>

                {{-- 네비게이션 --}}
                <div class="flex items-center justify-between mt-4">
                    <button @click="prev()" x-show="current > 0"
                        class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        &larr; 이전
                    </button>
                    <div x-show="current === 0"></div>

                    {{-- 문제 번호 점 --}}
                    <div class="flex gap-1.5 flex-wrap justify-center max-w-xs">
                        <template x-for="(q, qi) in questions" :key="'dot'+qi">
                            <button @click="current = qi"
                                class="w-3 h-3 rounded-full transition"
                                :class="{
                                    'bg-emerald-500': submitted[qi] && answers[qi] === q.correct_id,
                                    'bg-red-400': submitted[qi] && answers[qi] !== q.correct_id,
                                    'bg-indigo-600 scale-125': current === qi && !submitted[qi],
                                    'bg-gray-300': current !== qi && !submitted[qi],
                                }"></button>
                        </template>
                    </div>

                    <template x-if="current < total - 1">
                        <button @click="next()"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
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
                                class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 transition">
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
                        <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl text-lg font-bold hover:bg-emerald-700 transition shadow-lg">
                            시험 결과 확인 (<span x-text="correctCount"></span>/<span x-text="total"></span>)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

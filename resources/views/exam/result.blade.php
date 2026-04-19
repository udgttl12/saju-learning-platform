<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 점수 카드 --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm dark:shadow-slate-900/50 p-8 text-center">
                @php
                    $grade = $score >= 90 ? ['label' => '훌륭합니다!', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50']
                        : ($score >= 70 ? ['label' => '잘 했어요!', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50']
                        : ($score >= 50 ? ['label' => '조금 더 연습하면 돼요', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50']
                        : ['label' => '복습이 필요해요', 'color' => 'text-red-600', 'bg' => 'bg-red-50']));
                @endphp

                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full {{ $grade['bg'] }} mb-4">
                    <span class="text-4xl font-bold {{ $grade['color'] }}">{{ $score }}점</span>
                </div>
                <h2 class="text-xl font-bold {{ $grade['color'] }}">{{ $grade['label'] }}</h2>
                <p class="text-gray-500 dark:text-slate-400 mt-2">{{ $categoryLabel }} 시험 · {{ $correctCount }}/{{ $total }} 정답</p>

                @if($requestedCount > $total)
                    <p class="text-xs text-amber-500 mt-1">요청한 {{ $requestedCount }}문제 중 과목 내 가용 문제가 부족해 {{ $total }}문제로 출제되었습니다.</p>
                @endif

                @if($isHanjaCategory && $total - $correctCount > 0)
                    <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">틀린 {{ $total - $correctCount }}개 한자가 복습 카드에 등록되었습니다.</p>
                @endif
            </div>

            {{-- 문제별 결과 --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm dark:shadow-slate-900/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white">문제별 결과</h3>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700">
                    @foreach($results as $i => $r)
                    @php $hasChar = !empty($r['has_char']); @endphp
                    <div class="px-6 py-4 flex items-center gap-4 {{ $r['is_correct'] ? '' : 'bg-red-50/50 dark:bg-red-500/5' }}">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $r['is_correct'] ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $i + 1 }}
                        </span>
                        @if($hasChar)
                            <span class="text-3xl font-serif w-12 text-center">{{ $r['char_value'] }}</span>
                        @endif
                        <div class="flex-1 min-w-0">
                            @if($hasChar)
                                <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $r['meaning_ko'] }} ({{ $r['reading_ko'] }})</div>
                            @else
                                <div class="text-sm text-gray-700 dark:text-slate-300 truncate">{{ $r['prompt'] ?? '' }}</div>
                                <div class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">정답: <span class="font-semibold">{{ $r['meaning_ko'] }}</span></div>
                            @endif
                            @if(!$r['is_correct'])
                                <div class="text-xs text-red-500 mt-0.5">오답</div>
                            @endif
                        </div>
                        @if($r['is_correct'])
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 버튼 --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('exam.index') }}" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition text-center">
                    다시 시험보기
                </a>
                @if($total - $correctCount > 0)
                    <a href="{{ route('review.index') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition text-center">
                        복습하러 가기
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

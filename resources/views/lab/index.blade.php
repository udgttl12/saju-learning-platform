<x-app-layout>
    <div class="min-h-screen pb-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8">
            {{-- 히어로 타이틀 --}}
            <div class="text-center mb-10">
                <h2 class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent">
                    실전관
                </h2>
                <p class="mt-3 text-gray-600 dark:text-slate-400 text-sm sm:text-base">실제 사주 예시를 분석하면서 학습한 한자를 실전에서 확인해 보세요.</p>
            </div>

            @if($examples->isEmpty())
                <div class="bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <div class="text-gray-500 dark:text-slate-500 text-lg">등록된 샘플 차트가 없습니다.</div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($examples as $example)
                    <a href="{{ route('lab.show', $example->slug) }}"
                       class="bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl hover:border-amber-400/40 hover:bg-gray-50 dark:hover:bg-white/10 transition-all duration-300 block overflow-hidden group">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-2 group-hover:text-amber-400 transition-colors">{{ $example->title }}</h3>
                            @if($example->description)
                                <p class="text-sm text-gray-500 dark:text-slate-500 mb-4">{{ Str::limit($example->description, 80) }}</p>
                            @endif

                            {{-- Mini chart preview: 8글자 --}}
                            <div class="grid grid-cols-4 gap-2 text-center">
                                @foreach(['year' => '연', 'month' => '월', 'day' => '일', 'hour' => '시'] as $key => $label)
                                <div class="bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl p-2">
                                    <div class="text-[10px] text-gray-500 dark:text-slate-500 mb-1">{{ $label }}주</div>
                                    <div class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $example->{$key . '_stem'} }}</div>
                                    <div class="text-lg font-bold text-gray-700 dark:text-slate-300">{{ $example->{$key . '_branch'} }}</div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-slate-500">
                                <span>{{ $example->gender === 'M' ? '남성' : ($example->gender === 'F' ? '여성' : '') }}</span>
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 font-medium">Lv.{{ $example->difficulty_level }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

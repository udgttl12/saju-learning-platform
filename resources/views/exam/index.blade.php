<x-app-layout>
    <div class="bg-slate-900 min-h-screen pb-16">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 pt-8">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto bg-amber-500/20 text-amber-400 rounded-full flex items-center justify-center text-2xl mb-4 ring-2 ring-amber-400/30">試</div>
                    <h3 class="text-xl font-bold bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent">한자 뜻 맞히기</h3>
                    <p class="text-sm text-slate-500 mt-2">한자를 보고 뜻을 맞히는 4지 선다 시험입니다.<br>틀린 한자는 자동으로 복습 카드에 등록됩니다.</p>
                </div>

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-500/20 border border-red-500/30 text-red-400 rounded-xl text-sm">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('exam.start') }}" class="space-y-6">
                    @csrf

                    {{-- 카테고리 선택 --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">카테고리</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($categories as $key => $cat)
                                @if($cat['count'] >= 4)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="category" value="{{ $key }}" class="peer sr-only" {{ $key === 'all' ? 'checked' : '' }}>
                                    <div class="p-4 border-2 border-white/10 rounded-xl text-center transition-all
                                        peer-checked:border-amber-400 peer-checked:bg-amber-500/10 hover:border-white/20 hover:bg-white/5">
                                        <div class="font-semibold text-slate-200">{{ $cat['label'] }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $cat['count'] }}자</div>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- 문제 수 선택 --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">문제 수</label>
                        <div class="flex gap-2">
                            @foreach([5, 10, 15, 20, 27] as $n)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="count" value="{{ $n }}" class="peer sr-only" {{ $n === 10 ? 'checked' : '' }}>
                                <div class="py-3 border-2 border-white/10 rounded-xl text-center text-sm font-semibold transition-all text-slate-400
                                    peer-checked:border-amber-400 peer-checked:bg-amber-500/10 peer-checked:text-amber-400 hover:border-white/20 hover:bg-white/5">
                                    {{ $n }}문제
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 font-bold rounded-xl text-lg hover:from-amber-500 hover:to-yellow-600 transition-all shadow-lg shadow-amber-500/25">
                        시험 시작
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

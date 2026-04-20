<x-app-layout>
    <div class="min-h-screen pb-16">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 pt-8">
            <div class="bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto bg-amber-500/20 text-amber-400 rounded-full flex items-center justify-center text-2xl mb-4 ring-2 ring-amber-400/30">試</div>
                    <h3 class="text-xl font-bold bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent">사주 입문 시험</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-500 mt-2">
                        과목과 문제 수를 고르면 4지선다 시험을 시작할 수 있어요.<br>
                        한자 시험에서 틀린 문제는 자동으로 복습 카드에 등록돼요.
                    </p>
                </div>

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 rounded-xl text-sm">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('exam.start') }}" class="space-y-6"
                      x-data="{
                          selected: 'all',
                          chosenCount: 10,
                          counts: @js($categories),
                          countOptions: @js($countOptions),
                          maxCount() {
                              return this.counts[this.selected]?.count ?? 0;
                          },
                          availableCountOptions() {
                              return this.countOptions.filter(n => n <= this.maxCount());
                          },
                          syncChosenCount() {
                              const options = this.availableCountOptions();

                              if (options.length === 0) {
                                  this.chosenCount = 0;
                                  return;
                              }

                              if (!options.includes(this.chosenCount)) {
                                  this.chosenCount = options.includes(10)
                                      ? 10
                                      : options[options.length - 1];
                              }
                          },
                          init() {
                              this.syncChosenCount();
                              this.$watch('selected', () => this.syncChosenCount());
                          }
                      }">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">과목</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($categories as $key => $cat)
                                @if($cat['count'] >= 4)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="category" value="{{ $key }}" x-model="selected" class="peer sr-only" {{ $key === 'all' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 dark:border-white/10 rounded-xl text-center transition-all
                                            peer-checked:border-amber-400 peer-checked:bg-amber-500/10 hover:border-gray-300 dark:hover:border-white/20 hover:bg-gray-50 dark:hover:bg-white/5">
                                            <div class="font-semibold text-gray-800 dark:text-slate-200">{{ $cat['label'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-slate-500 mt-1">{{ $cat['count'] }}문제</div>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-slate-500">
                            선택한 과목: <span class="text-amber-500 font-semibold" x-text="counts[selected]?.label ?? ''"></span>
                            <span class="text-gray-400 dark:text-slate-600">총 최대 <span x-text="maxCount()"></span>문제</span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">문제 수</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <template x-for="n in availableCountOptions()" :key="n">
                                <label class="cursor-pointer">
                                    <input type="radio" name="count" :value="n" x-model.number="chosenCount" class="peer sr-only">
                                    <div class="py-3 border-2 border-gray-200 dark:border-white/10 rounded-xl text-center text-sm font-semibold transition-all text-gray-600 dark:text-slate-400
                                        peer-checked:border-amber-400 peer-checked:bg-amber-500/10 peer-checked:text-amber-400 hover:border-gray-300 dark:hover:border-white/20 hover:bg-gray-50 dark:hover:bg-white/5">
                                        <span x-text="`${n}문제`"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-slate-500">
                            선택한 과목에서 준비된 문제 수까지만 선택할 수 있어요.
                        </p>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 font-bold rounded-xl text-lg hover:from-amber-500 hover:to-yellow-600 transition-all shadow-lg shadow-amber-500/25">
                        시험 시작
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="min-h-screen pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">

            <!-- 인사말 -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->profile?->display_name ?? '학습자' }}님, 오늘의 학습</h1>
                <p class="text-gray-600 dark:text-slate-400 mt-1">꾸준히 한 걸음씩 나아가고 있어요.</p>
            </div>

            <!-- 요약 카드 3개 -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                <div class="bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-slate-400">등록 트랙</span>
                    </div>
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $enrollments->count() }}</div>
                </div>
                <div class="bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-rose-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-slate-400">복습 대기</span>
                    </div>
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $reviewDueCount }}</div>
                </div>
                <div class="bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-slate-400">완료 레슨</span>
                    </div>
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $recentAttempts->where('status', 'completed')->count() }}</div>
                </div>
            </div>

            <!-- 복습 CTA -->
            @if($reviewDueCount > 0)
            <div class="bg-gradient-to-r from-amber-500/10 to-yellow-500/10 border border-amber-500/20 rounded-2xl p-6 mb-10 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-gray-900 dark:text-white font-semibold">복습할 카드가 {{ $reviewDueCount }}개 있어요</h3>
                    <p class="text-gray-600 dark:text-slate-400 text-sm mt-1">잊기 전에 복습하면 기억이 오래 갑니다.</p>
                </div>
                <a href="{{ route('review.index') }}" class="px-6 py-3 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 font-bold rounded-xl hover:shadow-lg hover:shadow-amber-500/25 transition-all">
                    복습하러 가기
                </a>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- 진행 중인 트랙 -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">진행 중인 트랙</h2>
                    @forelse($enrollments as $enrollment)
                    <a href="{{ route('tracks.show', $enrollment->learningTrack->slug) }}"
                       class="block bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-5 mb-3 hover:border-amber-500/30 transition-all group">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 dark:text-white font-medium group-hover:text-amber-400 transition-colors">{{ $enrollment->learningTrack->title }}</h3>
                            <span class="text-amber-600 dark:text-amber-400 text-sm font-bold">{{ number_format($enrollment->progress_percent, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-amber-400 to-yellow-500 h-2 rounded-full transition-all" style="width: {{ $enrollment->progress_percent }}%"></div>
                        </div>
                        @if($enrollment->last_accessed_at)
                        <p class="text-xs text-gray-500 dark:text-slate-500 mt-2">마지막 학습: {{ $enrollment->last_accessed_at->diffForHumans() }}</p>
                        @endif
                    </a>
                    @empty
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-8 text-center">
                        <p class="text-gray-500 dark:text-slate-500 mb-4">아직 시작한 트랙이 없어요.</p>
                        <a href="{{ route('tracks.index') }}" class="px-6 py-3 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 font-bold rounded-xl hover:shadow-lg transition-all inline-block">
                            학습 시작하기
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- 최근 학습 -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">최근 학습</h2>
                    @forelse($recentAttempts->take(5) as $attempt)
                    <div class="flex items-center gap-4 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl p-4 mb-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $attempt->status === 'completed' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400' }}">
                            @if($attempt->status === 'completed')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate">{{ $attempt->lesson->title }}</p>
                            <p class="text-xs text-slate-500">{{ $attempt->last_accessed_at?->diffForHumans() ?? '' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-8 text-center">
                        <p class="text-gray-500 dark:text-slate-500">아직 학습 기록이 없어요.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

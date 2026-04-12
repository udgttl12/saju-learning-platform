<x-app-layout>
    <div class="bg-slate-900 min-h-screen pb-16">
        <!-- 히어로 -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">학습 여정</h1>
                <p class="text-slate-400">총 {{ $tracks->count() }}개 트랙 · {{ count($enrolledTrackIds) }}개 진행 중</p>
            </div>
        </div>

        <!-- 트랙 그리드 -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php $hanjaNum = ['一','二','三','四','五','六','七','八','九','十']; @endphp
                @forelse($tracks as $i => $track)
                    @php $enrolled = in_array($track->id, $enrolledTrackIds); @endphp
                    <a href="{{ route('tracks.show', $track->slug) }}"
                       class="relative bg-white/5 backdrop-blur-sm border rounded-2xl p-6 group hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300 block overflow-hidden
                       {{ $enrolled ? 'border-amber-500/40' : 'border-white/10' }}">
                        <!-- 한자 숫자 배경 -->
                        <span class="absolute -top-4 -right-2 text-8xl font-serif text-white/[0.03] select-none">{{ $hanjaNum[$i] ?? ($i+1) }}</span>

                        <div class="relative z-10">
                            <!-- 뱃지 -->
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $track->difficulty_level <= 1 ? 'bg-emerald-500/20 text-emerald-400' : '' }}
                                    {{ $track->difficulty_level == 2 ? 'bg-amber-500/20 text-amber-400' : '' }}
                                    {{ $track->difficulty_level >= 3 ? 'bg-rose-500/20 text-rose-400' : '' }}">
                                    @if($track->difficulty_level <= 1) 입문 @elseif($track->difficulty_level == 2) 중급 @else 심화 @endif
                                </span>
                                @if($enrolled)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400">학습 중</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-white mb-2 group-hover:text-amber-400 transition-colors">{{ $track->title }}</h3>
                            <p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ $track->short_description }}</p>

                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    {{ $track->lessons_count }}개 레슨
                                </span>
                                @if($track->estimated_total_minutes)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    약 {{ $track->estimated_total_minutes }}분
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- 화살표 -->
                        <div class="absolute bottom-6 right-6 text-slate-600 group-hover:text-amber-400 transition-colors">
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="text-slate-500 text-lg">아직 공개된 학습 트랙이 없습니다.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <!-- 전체 페이지 배경 -->
    <div class="bg-slate-900 text-slate-100 min-h-screen font-sans pb-20 overflow-hidden">
        <!-- Hero Section -->
        <section class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20 lg:py-32 flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <!-- 블러 효과 장식 (백그라운드 빛) -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/30 rounded-full blur-[128px] -z-10"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-[128px] -z-10"></div>

            <!-- 텍스트/CTA 영역 (Left) -->
            <div class="flex-1 text-center lg:text-left z-10 w-full animate-fade-in-up">
                <span class="inline-block py-1 px-3 rounded-full bg-amber-400/10 text-amber-400 text-sm font-semibold tracking-wider mb-6 border border-amber-400/20">
                    프리미엄 사주 학습 플랫폼
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    한자를 몰라도<br class="hidden lg:block">
                    시작할 수 있는 <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-yellow-500">사주 명리학 입문</span>
                </h1>
                <p class="mt-6 text-lg sm:text-lg lg:text-xl text-slate-400 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                    목·화·토·금·수부터 천간·지지까지, 복잡한 이론 대신 직관적으로. <br class="hidden sm:block">
                    하루 10분, 만세력 속 글자들이 하나의 이야기처럼 읽히기 시작합니다.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 font-bold rounded-xl shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] hover:-translate-y-0.5 transition-all duration-300 text-lg flex items-center justify-center gap-2 group">
                            이어서 학습하기
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900 font-bold rounded-xl shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] hover:-translate-y-0.5 transition-all duration-300 text-lg flex items-center justify-center gap-2 group">
                            무료로 시작하기
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 border border-slate-700 bg-slate-800/50 backdrop-blur-sm text-slate-300 font-semibold rounded-xl hover:bg-slate-700 hover:text-white transition-all duration-300 text-lg flex items-center justify-center">
                            로그인
                        </a>
                    @endauth
                </div>
            </div>

            <!-- 일러스트 영역 (Right) -->
            <div class="flex-1 w-full max-w-lg lg:max-w-xl relative z-10 flex justify-center mt-12 lg:mt-0 animate-fade-in">
                <div class="relative w-full aspect-[4/3] lg:aspect-square group">
                    <!-- 신비로운 이펙트 -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-amber-500/20 to-indigo-500/20 rounded-3xl rotate-3 blur-xl opacity-60 group-hover:rotate-6 transition-all duration-700"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-rose-500/20 rounded-3xl -rotate-3 blur-lg opacity-40 group-hover:-rotate-6 transition-all duration-700"></div>
                    <!-- 실제 이미지 (Glassmorphism 프레임) -->
                    <img src="{{ asset('images/hero.png') }}" alt="사주 입문 3D 일러스트" class="relative w-full h-full object-cover rounded-3xl shadow-2xl shadow-indigo-950/80 border border-slate-700/50 z-10 transition-transform duration-700 group-hover:scale-[1.02]">
                    <!-- 장식용 부유 요소들 -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl z-20 flex items-center justify-center transform rotate-12 shadow-xl hidden md:flex animate-bounce" style="animation-duration: 4s;">
                        <span class="text-amber-400 font-serif text-3xl font-bold opacity-80">木</span>
                    </div>
                    <div class="absolute -bottom-8 -left-6 w-20 h-20 bg-slate-900/40 backdrop-blur-xl border border-slate-700/50 rounded-full z-20 flex items-center justify-center transform -rotate-12 shadow-xl hidden md:flex animate-pulse">
                        <span class="text-rose-400 font-serif text-2xl font-bold opacity-80">火</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 학습 단계 섹션 -->
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-24 relative">
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-slate-700/60 to-transparent"></div>
            <div class="text-center mb-16 relative z-10">
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-300">이렇게 배웁니다</h2>
                <p class="mt-4 text-slate-400 font-light text-lg">입문자를 위해 설계된 체계적이고 직관적인 3단계 학습 프로세스</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8 relative z-10">
                <!-- 카드 1 -->
                <div class="bg-slate-800/30 backdrop-blur-md border border-slate-700/50 rounded-3xl p-8 lg:p-10 hover:bg-slate-800/60 hover:border-emerald-500/30 transition-all duration-300 group hover:-translate-y-2 hover:shadow-[0_15px_30px_-10px_rgba(16,185,129,0.15)] ring-1 ring-white/5">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 text-emerald-400 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-inner ring-1 ring-emerald-500/20 group-hover:scale-110 group-hover:rotate-3 transition-transform">읽</div>
                    <h3 class="font-bold text-2xl text-white mb-4">직관적인 읽기</h3>
                    <p class="text-slate-400 leading-relaxed font-light">오행, 천간, 지지 핵심 27자를 카드로 익힙니다. 어려운 한자 대신 뜻과 역할, 스토리 중심으로 한눈에 이해합니다.</p>
                </div>
                <!-- 카드 2 -->
                <div class="bg-slate-800/30 backdrop-blur-md border border-slate-700/50 rounded-3xl p-8 lg:p-10 hover:bg-slate-800/60 hover:border-rose-500/30 transition-all duration-300 group hover:-translate-y-2 hover:shadow-[0_15px_30px_-10px_rgba(244,63,94,0.15)] ring-1 ring-white/5">
                    <div class="w-16 h-16 bg-gradient-to-br from-rose-500/20 to-rose-500/5 text-rose-400 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-inner ring-1 ring-rose-500/20 group-hover:scale-110 group-hover:rotate-3 transition-transform">쓰</div>
                    <h3 class="font-bold text-2xl text-white mb-4">체득하는 쓰기</h3>
                    <p class="text-slate-400 leading-relaxed font-light">눈으로만 보지 않습니다. 따라쓰기, 겹쳐보기, 자유쓰기를 통해 손끝에서부터 글자의 감각을 단단하게 새깁니다.</p>
                </div>
                <!-- 카드 3 -->
                <div class="bg-slate-800/30 backdrop-blur-md border border-slate-700/50 rounded-3xl p-8 lg:p-10 hover:bg-slate-800/60 hover:border-cyan-500/30 transition-all duration-300 group hover:-translate-y-2 hover:shadow-[0_15px_30px_-10px_rgba(6,182,212,0.15)] ring-1 ring-white/5">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500/20 to-cyan-500/5 text-cyan-400 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-inner ring-1 ring-cyan-500/20 group-hover:scale-110 group-hover:rotate-3 transition-transform">연</div>
                    <h3 class="font-bold text-2xl text-white mb-4">실전 연결하기</h3>
                    <p class="text-slate-400 leading-relaxed font-light">배운 글자를 낱개로 두지 않습니다. 실제 만세력 샘플에 바로 적용하여 진짜 사주 읽기의 기초를 완성합니다.</p>
                </div>
            </div>
        </section>

        <!-- 커리큘럼 미리보기 -->
        <section class="bg-slate-950/60 py-24 relative overflow-hidden">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-white">5단계 핵심 커리큘럼</h2>
                    <p class="mt-4 text-slate-400 font-light text-lg">차근차근 따라오면 누구나 이해할 수 있습니다</p>
                </div>
                
                @php
                    $tracks = \App\Models\LearningTrack::where('publish_status', 'published')
                        ->orderBy('sort_order')
                        ->withCount('lessons')
                        ->get();
                @endphp
                
                <div class="space-y-5">
                    @foreach($tracks as $i => $track)
                    <a href="{{ route('tracks.show', $track->slug) }}" class="group block bg-slate-800/40 backdrop-blur-md border border-slate-700/40 rounded-2xl p-6 hover:bg-slate-800/80 hover:border-amber-500/50 hover:shadow-[0_8px_30px_rgba(245,158,11,0.1)] transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center gap-5 sm:gap-8">
                            <span class="flex-shrink-0 w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-slate-900 border border-slate-700 text-amber-400 flex items-center justify-center text-xl sm:text-2xl font-extrabold font-mono group-hover:scale-110 group-hover:bg-amber-400/10 transition-all shadow-inner">0{{ $i + 1 }}</span>
                            <div class="flex-1">
                                <h3 class="text-xl sm:text-2xl font-bold text-slate-100 group-hover:text-amber-400 transition-colors">{{ $track->title }}</h3>
                                <p class="text-sm sm:text-base text-slate-400 mt-2 line-clamp-2 md:line-clamp-1 font-light">{{ $track->short_description }}</p>
                            </div>
                            <div class="text-sm text-slate-500 hidden md:flex items-center gap-3 bg-slate-900/50 px-5 py-2.5 rounded-xl border border-slate-800/80">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                레슨 <span class="text-slate-300 font-semibold">{{ $track->lessons_count }}</span>개 <span class="mx-1 text-slate-700">|</span> <span class="text-slate-300 font-semibold">{{ $track->estimated_total_minutes }}</span>분
                            </div>
                            <!-- Arrow Icon -->
                            <div class="hidden sm:flex ml-2 text-slate-600 group-hover:text-amber-400 group-hover:translate-x-2 transition-all">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            
            <!-- BG Elements for Curriculum -->
            <div class="absolute top-1/2 left-0 w-72 h-72 bg-blue-500/5 rounded-full blur-[100px] -translate-y-1/2 pointer-events-none"></div>
            <div class="absolute top-1/2 right-0 w-72 h-72 bg-amber-500/5 rounded-full blur-[100px] -translate-y-1/2 pointer-events-none"></div>
        </section>

        <!-- 오행 미리보기 -->
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-24 relative">
            <div class="text-center mb-16 relative z-10">
                <span class="inline-block text-amber-500 font-bold tracking-[0.2em] text-sm mb-3">미리보기</span>
                <h2 class="text-3xl font-bold text-white">핵심 한자 미리보기</h2>
                <p class="mt-4 text-slate-400 font-light text-lg">만물의 기본, 오행 5글자부터 시작해볼까요?</p>
            </div>
            
            @php
                $elements = \App\Models\HanjaChar::where('category', 'five_elements')
                    ->where('publish_status', 'published')
                    ->orderBy('id')
                    ->get();
                $colors = [
                    'wood'  => 'from-emerald-500 to-emerald-400 shadow-emerald-500/20 text-emerald-50 ring-emerald-400/30 glow-emerald',
                    'fire'  => 'from-rose-500 to-rose-400 shadow-rose-500/20 text-rose-50 ring-rose-400/30 glow-rose',
                    'earth' => 'from-amber-500 to-amber-400 shadow-amber-500/20 text-amber-50 ring-amber-400/30 glow-amber',
                    'metal' => 'from-slate-400 to-slate-300 shadow-slate-500/20 text-slate-50 ring-slate-300/30 glow-slate',
                    'water' => 'from-indigo-500 to-blue-400 shadow-blue-500/20 text-blue-50 ring-blue-400/30 glow-blue',
                ];
            @endphp
            
            <div class="flex justify-center gap-6 md:gap-10 flex-wrap relative z-10">
                @foreach($elements as $el)
                <a href="{{ route('hanja.show', $el->slug) }}" class="group flex flex-col items-center">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br {{ $colors[$el->element] ?? 'from-gray-600 to-gray-500' }} rounded-[2rem] flex items-center justify-center text-4xl md:text-5xl font-bold shadow-xl ring-1 ring-inset group-hover:-translate-y-3 group-hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                        <!-- 광택 효과 -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/25 to-white/0 translate-x-[-150%] skew-x-[-45deg] group-hover:translate-x-[150%] transition-transform duration-1000 ease-out"></div>
                        <span class="relative z-10 drop-shadow-md">{{ $el->char_value }}</span>
                    </div>
                    <div class="mt-6 text-center bg-slate-800/80 px-6 py-2.5 rounded-2xl border border-slate-700/50 backdrop-blur-sm group-hover:border-amber-500/40 group-hover:bg-slate-800 group-hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <p class="font-bold text-white text-lg tracking-wide">{{ $el->reading_ko }}</p>
                        <p class="text-sm text-slate-400 mt-1 font-light">{{ $el->meaning_ko }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        <!-- CTA -->
        <section class="max-w-5xl mx-auto px-6 lg:px-8 py-20 pb-32">
            <div class="bg-gradient-to-t from-slate-900 to-indigo-950/40 border border-indigo-500/20 rounded-[2.5rem] p-10 md:p-16 lg:p-20 text-center shadow-2xl relative overflow-hidden ring-1 ring-white/5">
                <!-- BG Decoration -->
                <div class="absolute -top-32 -right-32 w-80 h-80 bg-amber-500/10 rounded-full blur-[80px] mix-blend-screen pointer-events-none"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-[80px] mix-blend-screen pointer-events-none"></div>
                
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 relative z-10 leading-snug">지금 시작하면, 오늘 <br class="hidden sm:block"> 오행 5글자를 읽을 수 있습니다.</h2>
                <p class="text-slate-300 mb-12 text-lg md:text-xl relative z-10 max-w-2xl mx-auto font-light leading-relaxed">회원가입 없이도 전체 커리큘럼과 한자 사전을 자유롭게 둘러보실 수 있습니다. 디지털 시대에 맞는 새로운 사주 명리학 학습을 직접 경험해 보세요.</p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center relative z-10">
                    <a href="{{ route('tracks.index') }}" class="px-8 py-4 bg-white text-indigo-950 font-bold rounded-xl hover:bg-amber-400 hover:text-slate-900 shadow-xl hover:shadow-amber-500/20 transition-all duration-300 text-lg flex items-center justify-center group">
                        전체 코스 보기
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('dictionary.index') }}" class="px-8 py-4 border border-slate-600 bg-slate-800/80 text-white font-medium rounded-xl hover:bg-slate-700 hover:border-slate-400 transition-all duration-300 text-lg backdrop-blur-sm flex items-center justify-center">
                        한자 사전 검색
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>

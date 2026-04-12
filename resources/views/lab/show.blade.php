@php
    $elementColors = [
        'wood'  => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/40', 'glow' => 'shadow-emerald-500/20'],
        'fire'  => ['bg' => 'bg-rose-500/20',    'text' => 'text-rose-400',    'border' => 'border-rose-500/40',    'glow' => 'shadow-rose-500/20'],
        'earth' => ['bg' => 'bg-amber-500/20',   'text' => 'text-amber-400',   'border' => 'border-amber-500/40',   'glow' => 'shadow-amber-500/20'],
        'metal' => ['bg' => 'bg-slate-400/20',   'text' => 'text-slate-300',   'border' => 'border-slate-400/40',   'glow' => 'shadow-slate-400/20'],
        'water' => ['bg' => 'bg-blue-500/20',    'text' => 'text-blue-400',    'border' => 'border-blue-500/40',    'glow' => 'shadow-blue-500/20'],
    ];

    // Map stems/branches to elements (simplified mapping for display)
    $stemElements = [
        '甲' => 'wood', '乙' => 'wood',
        '丙' => 'fire', '丁' => 'fire',
        '戊' => 'earth', '己' => 'earth',
        '庚' => 'metal', '辛' => 'metal',
        '壬' => 'water', '癸' => 'water',
    ];

    $branchElements = [
        '子' => 'water', '丑' => 'earth', '寅' => 'wood', '卯' => 'wood',
        '辰' => 'earth', '巳' => 'fire', '午' => 'fire', '未' => 'earth',
        '申' => 'metal', '酉' => 'metal', '戌' => 'earth', '亥' => 'water',
    ];
@endphp

<x-app-layout>
    <div class="bg-slate-900 min-h-screen pb-16">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 pt-8">
            {{-- 타이틀 --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent">
                    {{ $example->title }}
                </h2>
            </div>

            {{-- Info --}}
            @if($example->description)
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 mb-6">
                    <p class="text-slate-300">{{ $example->description }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                        @if($example->gender)
                            <span>{{ $example->gender === 'M' ? '남성' : '여성' }}</span>
                        @endif
                        @if($example->solar_birth_datetime)
                            <span>양력: {{ $example->solar_birth_datetime->format('Y년 m월 d일 H:i') }}</span>
                        @endif
                        @if($example->lunar_birth_label)
                            <span>음력: {{ $example->lunar_birth_label }}</span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Saju Chart (8 characters table) --}}
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 mb-6">
                <h3 class="text-lg font-semibold text-slate-200 mb-8 text-center tracking-wide">사주 팔자</h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 max-w-2xl mx-auto">
                    @foreach(['hour' => '시주', 'day' => '일주', 'month' => '월주', 'year' => '연주'] as $key => $label)
                    @php
                        $stem = $pillars[$key]['stem'];
                        $branch = $pillars[$key]['branch'];
                        $stemEl = $stemElements[$stem] ?? null;
                        $branchEl = $branchElements[$branch] ?? null;
                        $stemColor = $stemEl ? $elementColors[$stemEl] : ['bg' => 'bg-white/5', 'text' => 'text-slate-300', 'border' => 'border-white/10', 'glow' => ''];
                        $branchColor = $branchEl ? $elementColors[$branchEl] : ['bg' => 'bg-white/5', 'text' => 'text-slate-300', 'border' => 'border-white/10', 'glow' => ''];
                        $isDayStem = ($key === 'day');
                    @endphp
                    <div class="text-center">
                        <div class="text-xs text-slate-500 mb-3 font-medium tracking-wider uppercase">{{ $label }}</div>
                        {{-- Stem (Heavenly) --}}
                        <div class="border-2 {{ $stemColor['border'] }} {{ $stemColor['bg'] }} rounded-xl p-4 mb-3 transition-all hover:scale-105
                            {{ $isDayStem ? 'ring-2 ring-amber-400/50 shadow-lg shadow-amber-400/20' : '' }}"
                             title="{{ $stemEl ? ['wood'=>'목(木)', 'fire'=>'화(火)', 'earth'=>'토(土)', 'metal'=>'금(金)', 'water'=>'수(水)'][$stemEl] : '' }}">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold {{ $isDayStem ? 'text-amber-400 drop-shadow-[0_0_12px_rgba(251,191,36,0.5)]' : $stemColor['text'] }}">{{ $stem }}</div>
                            <div class="text-[10px] text-slate-500 mt-2">천간{{ $isDayStem ? ' (일간)' : '' }}</div>
                        </div>
                        {{-- Branch (Earthly) --}}
                        <div class="border-2 {{ $branchColor['border'] }} {{ $branchColor['bg'] }} rounded-xl p-4 transition-all hover:scale-105"
                             title="{{ $branchEl ? ['wood'=>'목(木)', 'fire'=>'화(火)', 'earth'=>'토(土)', 'metal'=>'금(金)', 'water'=>'수(水)'][$branchEl] : '' }}">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold {{ $branchColor['text'] }}">{{ $branch }}</div>
                            <div class="text-[10px] text-slate-500 mt-2">지지</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Element legend --}}
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4 text-xs">
                    @foreach([
                        'wood'  => '목(木)',
                        'fire'  => '화(火)',
                        'earth' => '토(土)',
                        'metal' => '금(金)',
                        'water' => '수(水)',
                    ] as $el => $name)
                    <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $elementColors[$el]['bg'] }} border {{ $elementColors[$el]['border'] }}">
                        <span class="w-2.5 h-2.5 rounded-full {{ $elementColors[$el]['bg'] }} {{ $elementColors[$el]['border'] }} border"></span>
                        <span class="{{ $elementColors[$el]['text'] }}">{{ $name }}</span>
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('lab.index') }}" class="text-sm text-slate-500 hover:text-amber-400 transition-colors">&larr; 목록으로 돌아가기</a>
            </div>
        </div>
    </div>
</x-app-layout>

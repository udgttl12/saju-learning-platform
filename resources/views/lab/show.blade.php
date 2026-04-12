@php
    $elementColors = [
        'wood' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300'],
        'fire' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300'],
        'earth' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300'],
        'metal' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300'],
        'water' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300'],
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
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $example->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Info --}}
            @if($example->description)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <p class="text-gray-700">{{ $example->description }}</p>
                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
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
            <div class="bg-white rounded-lg shadow p-8 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6 text-center">사주 팔자</h3>

                <div class="grid grid-cols-4 gap-4 max-w-lg mx-auto">
                    @foreach(['hour' => '시주', 'day' => '일주', 'month' => '월주', 'year' => '연주'] as $key => $label)
                    @php
                        $stem = $pillars[$key]['stem'];
                        $branch = $pillars[$key]['branch'];
                        $stemEl = $stemElements[$stem] ?? null;
                        $branchEl = $branchElements[$branch] ?? null;
                        $stemColor = $stemEl ? $elementColors[$stemEl] : ['bg' => 'bg-gray-50', 'text' => 'text-gray-800', 'border' => 'border-gray-200'];
                        $branchColor = $branchEl ? $elementColors[$branchEl] : ['bg' => 'bg-gray-50', 'text' => 'text-gray-800', 'border' => 'border-gray-200'];
                    @endphp
                    <div class="text-center">
                        <div class="text-xs text-gray-400 mb-2">{{ $label }}</div>
                        {{-- Stem (Heavenly) --}}
                        <div class="border-2 {{ $stemColor['border'] }} {{ $stemColor['bg'] }} rounded-lg p-3 mb-2 cursor-pointer hover:shadow-md transition-shadow"
                             title="{{ $stemEl ? ['wood'=>'목(木)', 'fire'=>'화(火)', 'earth'=>'토(土)', 'metal'=>'금(金)', 'water'=>'수(水)'][$stemEl] : '' }}">
                            <div class="text-3xl font-bold {{ $stemColor['text'] }}">{{ $stem }}</div>
                            <div class="text-xs text-gray-500 mt-1">천간</div>
                        </div>
                        {{-- Branch (Earthly) --}}
                        <div class="border-2 {{ $branchColor['border'] }} {{ $branchColor['bg'] }} rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow"
                             title="{{ $branchEl ? ['wood'=>'목(木)', 'fire'=>'화(火)', 'earth'=>'토(土)', 'metal'=>'금(金)', 'water'=>'수(水)'][$branchEl] : '' }}">
                            <div class="text-3xl font-bold {{ $branchColor['text'] }}">{{ $branch }}</div>
                            <div class="text-xs text-gray-500 mt-1">지지</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Element legend --}}
                <div class="mt-8 flex items-center justify-center gap-4 text-xs">
                    @foreach(['wood' => '목(木)', 'fire' => '화(火)', 'earth' => '토(土)', 'metal' => '금(金)', 'water' => '수(水)'] as $el => $name)
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded {{ $elementColors[$el]['bg'] }} border {{ $elementColors[$el]['border'] }}"></span>
                        {{ $name }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('lab.index') }}" class="text-sm text-gray-500 hover:text-gray-700">목록으로 돌아가기</a>
            </div>
        </div>
    </div>
</x-app-layout>

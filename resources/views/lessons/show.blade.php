<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">
                    <a href="{{ route('tracks.show', $lesson->learningTrack->slug) }}" class="hover:text-indigo-600">
                        {{ $lesson->learningTrack->title }}
                    </a>
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $lesson->title }}
                </h2>
            </div>
            <a href="{{ route('tracks.show', $lesson->learningTrack->slug) }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; 트랙으로</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 레슨 목표 --}}
            @if($lesson->objective)
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <div class="text-xs font-medium text-indigo-500 mb-1">학습 목표</div>
                    <p class="text-sm text-indigo-800">{{ $lesson->objective }}</p>
                </div>
            @endif

            {{-- Step 기반 레슨 플레이어 --}}
            <div x-data="{ currentStep: 0, totalSteps: {{ $lesson->steps->count() }} }" class="space-y-4">

                {{-- 진행 바 --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                        <span>진행률</span>
                        <span x-text="Math.round(((currentStep + 1) / totalSteps) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                             :style="'width: ' + ((currentStep + 1) / totalSteps * 100) + '%'"></div>
                    </div>
                </div>

                {{-- Step 컨텐츠 --}}
                @foreach($lesson->steps as $stepIndex => $step)
                    <div x-show="currentStep === {{ $stepIndex }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                        {{-- Step 헤더 --}}
                        <div class="px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                @php
                                    $stepTypeLabel = match($step->step_type) {
                                        'intro' => '도입',
                                        'explanation' => '설명',
                                        'stroke_order' => '필순 학습',
                                        'guided_practice' => '연습',
                                        'quiz' => '퀴즈',
                                        'summary' => '정리',
                                        default => $step->step_type,
                                    };
                                    $stepTypeColor = match($step->step_type) {
                                        'intro' => 'bg-blue-100 text-blue-700',
                                        'explanation' => 'bg-purple-100 text-purple-700',
                                        'stroke_order' => 'bg-amber-100 text-amber-700',
                                        'guided_practice' => 'bg-emerald-100 text-emerald-700',
                                        'quiz' => 'bg-red-100 text-red-700',
                                        'summary' => 'bg-gray-100 text-gray-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stepTypeColor }}">
                                    {{ $stepTypeLabel }}
                                </span>
                                @if($step->title)
                                    <h3 class="text-base font-semibold text-gray-800">{{ $step->title }}</h3>
                                @endif
                            </div>
                        </div>

                        {{-- Step 본문 --}}
                        <div class="px-6 py-6">
                            @switch($step->step_type)
                                @case('intro')
                                    <div class="prose prose-sm max-w-none text-gray-700">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                                    @break

                                @case('explanation')
                                    <div class="prose prose-sm max-w-none text-gray-700">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                                    @if($step->payload_json && isset($step->payload_json['hanja_chars']))
                                        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                                            @foreach($step->payload_json['hanja_chars'] as $charSlug)
                                                @php
                                                    $hc = $lesson->hanjaChars->firstWhere('slug', $charSlug);
                                                @endphp
                                                @if($hc)
                                                    <a href="{{ route('hanja.show', $hc->slug) }}"
                                                       class="block p-4 border border-gray-200 rounded-lg text-center hover:border-indigo-300 transition">
                                                        <div class="text-4xl mb-2">{{ $hc->char_value }}</div>
                                                        <div class="text-sm font-medium text-gray-800">{{ $hc->reading_ko }}</div>
                                                        <div class="text-xs text-gray-500">{{ $hc->meaning_ko }}</div>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('stroke_order')
                                    <div class="text-center py-6">
                                        @if($step->payload_json && isset($step->payload_json['char_value']))
                                            <div class="text-8xl mb-4 font-serif">{{ $step->payload_json['char_value'] }}</div>
                                            @if(isset($step->payload_json['stroke_count']))
                                                <p class="text-sm text-gray-500 mb-2">총 {{ $step->payload_json['stroke_count'] }}획</p>
                                            @endif
                                        @endif
                                        <div class="prose prose-sm max-w-none text-gray-700 text-left mt-4">
                                            {!! nl2br(e($step->content_markdown)) !!}
                                        </div>
                                    </div>
                                    @break

                                @case('guided_practice')
                                    <div class="prose prose-sm max-w-none text-gray-700 mb-4">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                                    @if($step->payload_json && isset($step->payload_json['practice_items']))
                                        <div class="space-y-3 mt-4">
                                            @foreach($step->payload_json['practice_items'] as $item)
                                                <div class="p-4 bg-gray-50 rounded-lg">
                                                    <p class="text-sm text-gray-700">{{ $item['prompt'] ?? $item }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('quiz')
                                    <div class="prose prose-sm max-w-none text-gray-700 mb-4">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                                    @if($step->payload_json && isset($step->payload_json['questions']))
                                        <div x-data="{ answers: {}, revealed: {} }" class="space-y-6 mt-4">
                                            @foreach($step->payload_json['questions'] as $qi => $question)
                                                <div class="p-4 bg-gray-50 rounded-lg">
                                                    <p class="text-sm font-medium text-gray-800 mb-3">{{ $qi + 1 }}. {{ $question['question'] ?? '' }}</p>
                                                    @if(isset($question['options']))
                                                        <div class="space-y-2">
                                                            @foreach($question['options'] as $oi => $option)
                                                                <button
                                                                    @click="answers[{{ $qi }}] = {{ $oi }}; revealed[{{ $qi }}] = true"
                                                                    :class="{
                                                                        'border-indigo-500 bg-indigo-50': answers[{{ $qi }}] === {{ $oi }} && !revealed[{{ $qi }}],
                                                                        'border-emerald-500 bg-emerald-50': revealed[{{ $qi }}] && {{ $oi }} === {{ $question['answer'] ?? 0 }},
                                                                        'border-red-300 bg-red-50': revealed[{{ $qi }}] && answers[{{ $qi }}] === {{ $oi }} && {{ $oi }} !== {{ $question['answer'] ?? 0 }},
                                                                    }"
                                                                    class="w-full text-left px-4 py-2.5 border border-gray-200 rounded-md text-sm hover:border-indigo-300 transition">
                                                                    {{ $option }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break

                                @case('summary')
                                    <div class="prose prose-sm max-w-none text-gray-700">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                                    {{-- 관련 한자 카드 목록 --}}
                                    @if($lesson->hanjaChars->isNotEmpty())
                                        <div class="mt-6">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3">이번 레슨에서 배운 한자</h4>
                                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                                @foreach($lesson->hanjaChars as $hc)
                                                    <a href="{{ route('hanja.show', $hc->slug) }}"
                                                       class="block p-3 border border-gray-200 rounded-lg text-center hover:border-indigo-300 transition">
                                                        <div class="text-2xl mb-1">{{ $hc->char_value }}</div>
                                                        <div class="text-xs text-gray-600">{{ $hc->reading_ko }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @break

                                @default
                                    <div class="prose prose-sm max-w-none text-gray-700">
                                        {!! nl2br(e($step->content_markdown)) !!}
                                    </div>
                            @endswitch
                        </div>
                    </div>
                @endforeach

                {{-- 네비게이션 버튼 --}}
                <div class="flex items-center justify-between bg-white shadow-sm sm:rounded-lg p-4">
                    <button @click="currentStep = Math.max(0, currentStep - 1)"
                            x-show="currentStep > 0"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                        &larr; 이전
                    </button>
                    <div x-show="currentStep === 0"></div>

                    <span class="text-sm text-gray-400" x-text="(currentStep + 1) + ' / ' + totalSteps"></span>

                    <button @click="currentStep = Math.min(totalSteps - 1, currentStep + 1)"
                            x-show="currentStep < totalSteps - 1"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                        다음 &rarr;
                    </button>

                    {{-- 마지막 스텝에서 완료 버튼 --}}
                    <div x-show="currentStep === totalSteps - 1">
                        @if($attempt->status !== 'completed')
                            <form action="{{ route('lessons.complete', $lesson->slug) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition">
                                    레슨 완료!
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-md">
                                이미 완료됨
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

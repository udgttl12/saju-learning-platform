<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $track->title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $track->short_description }}</p>
            </div>
            <a href="{{ route('tracks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; 트랙 목록</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 플래시 메시지 --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 트랙 정보 카드 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $track->difficulty_level <= 1 ? 'bg-green-100 text-green-700' : '' }}
                                {{ $track->difficulty_level == 2 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $track->difficulty_level >= 3 ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                @if($track->difficulty_level <= 1) 입문
                                @elseif($track->difficulty_level == 2) 중급
                                @else 고급
                                @endif
                            </span>
                            <span>{{ $track->lessons->count() }}개 레슨</span>
                            @if($track->estimated_total_minutes)
                                <span>약 {{ $track->estimated_total_minutes }}분</span>
                            @endif
                        </div>
                        @if($track->target_audience)
                            <p class="text-sm text-gray-600">대상: {{ $track->target_audience }}</p>
                        @endif
                    </div>

                    @auth
                        @if($enrollment)
                            <div class="text-right">
                                <div class="text-sm text-gray-500 mb-1">진행률 {{ number_format($enrollment->progress_percent, 0) }}%</div>
                                <div class="w-48 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percent }}%"></div>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('tracks.enroll', $track->slug) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    학습 시작하기
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            로그인 후 시작하기
                        </a>
                    @endauth
                </div>
            </div>

            {{-- 레슨 목록 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">레슨 목록</h3>
                <div class="space-y-3">
                    @foreach($track->lessons as $index => $lesson)
                        @php
                            $isCompleted = in_array($lesson->id, $completedLessonIds);
                            // 잠금 상태: 첫 레슨은 항상 열림, 이전 레슨이 완료되어야 다음 열림
                            $isLocked = false;
                            if ($enrollment && $index > 0) {
                                $prevLesson = $track->lessons[$index - 1];
                                $isLocked = !in_array($prevLesson->id, $completedLessonIds);
                            }
                            if (!$enrollment) {
                                $isLocked = $index > 0; // 미등록 시 첫 번째만 표시
                            }
                        @endphp

                        <div class="flex items-center border border-gray-200 rounded-lg p-4 {{ $isLocked ? 'opacity-50' : 'hover:border-indigo-300' }} transition">
                            {{-- 번호 / 상태 아이콘 --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-4
                                {{ $isCompleted ? 'bg-emerald-100 text-emerald-600' : ($isLocked ? 'bg-gray-100 text-gray-400' : 'bg-indigo-100 text-indigo-600') }}">
                                @if($isCompleted)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($isLocked)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>

                            {{-- 레슨 정보 --}}
                            <div class="flex-1 min-w-0">
                                @if(!$isLocked && $enrollment)
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition">
                                        {{ $lesson->title }}
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-900">{{ $lesson->title }}</span>
                                @endif
                                @if($lesson->objective)
                                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $lesson->objective }}</p>
                                @endif
                            </div>

                            {{-- 메타 --}}
                            <div class="flex-shrink-0 flex items-center gap-3 text-xs text-gray-400 ml-4">
                                @if($lesson->estimated_minutes)
                                    <span>{{ $lesson->estimated_minutes }}분</span>
                                @endif
                                @if($lesson->lesson_type)
                                    <span class="capitalize">{{ $lesson->lesson_type }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

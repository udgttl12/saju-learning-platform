<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            대시보드
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 요약 카드 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- 등록 트랙 수 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">등록된 트랙</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $enrollments->count() }}</div>
                </div>

                {{-- 복습 대기 카드 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">복습 대기 카드</div>
                    <div class="text-3xl font-bold text-amber-600">{{ $reviewDueCount }}</div>
                </div>

                {{-- 완료 레슨 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">완료한 레슨</div>
                    <div class="text-3xl font-bold text-emerald-600">{{ $recentAttempts->where('status', 'completed')->count() }}</div>
                </div>
            </div>

            {{-- 등록 트랙 진행률 --}}
            @if($enrollments->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">내 학습 트랙</h3>
                    <div class="space-y-4">
                        @foreach($enrollments as $enrollment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <a href="{{ route('tracks.show', $enrollment->learningTrack->slug) }}"
                                       class="text-base font-medium text-indigo-700 hover:underline">
                                        {{ $enrollment->learningTrack->title }}
                                    </a>
                                    <span class="text-sm text-gray-500">{{ number_format($enrollment->progress_percent, 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300"
                                         style="width: {{ $enrollment->progress_percent }}%"></div>
                                </div>
                                <div class="mt-1 text-xs text-gray-400">
                                    마지막 접근: {{ $enrollment->last_accessed_at?->diffForHumans() ?? '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 최근 레슨 --}}
            @if($recentAttempts->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">최근 학습 레슨</h3>
                    <div class="divide-y divide-gray-100">
                        @foreach($recentAttempts as $attempt)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('lessons.show', $attempt->lesson->slug) }}"
                                       class="text-sm font-medium text-gray-800 hover:text-indigo-600">
                                        {{ $attempt->lesson->title }}
                                    </a>
                                    <span class="text-xs text-gray-400 ml-2">
                                        {{ $attempt->lesson->learningTrack->title ?? '' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($attempt->status === 'completed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">완료</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">진행중</span>
                                    @endif
                                    <span class="text-xs text-gray-400">{{ $attempt->last_accessed_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 빈 상태 --}}
            @if($enrollments->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <div class="text-gray-400 text-5xl mb-4">&#x1F4DA;</div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">아직 등록된 트랙이 없습니다</h3>
                    <p class="text-sm text-gray-500 mb-4">학습 트랙을 둘러보고 시작해보세요.</p>
                    <a href="{{ route('tracks.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                        트랙 둘러보기
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

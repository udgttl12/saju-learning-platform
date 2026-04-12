<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            학습 트랙
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tracks as $track)
                    <a href="{{ route('tracks.show', $track->slug) }}"
                       class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md hover:border-indigo-200 border border-transparent transition-all group">
                        <div class="p-6">
                            {{-- 난이도 뱃지 --}}
                            <div class="flex items-center justify-between mb-3">
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
                                @if(in_array($track->id, $enrolledTrackIds))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">등록됨</span>
                                @endif
                            </div>

                            {{-- 트랙 제목 --}}
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                                {{ $track->title }}
                            </h3>

                            {{-- 설명 --}}
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                                {{ $track->short_description }}
                            </p>

                            {{-- 메타 정보 --}}
                            <div class="flex items-center text-xs text-gray-400 space-x-4">
                                <span>{{ $track->lessons_count }}개 레슨</span>
                                @if($track->estimated_total_minutes)
                                    <span>약 {{ $track->estimated_total_minutes }}분</span>
                                @endif
                            </div>

                            {{-- 화살표 --}}
                            <div class="mt-4 flex items-center text-sm font-medium text-indigo-600 group-hover:text-indigo-800 transition">
                                자세히 보기
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">아직 공개된 학습 트랙이 없습니다.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

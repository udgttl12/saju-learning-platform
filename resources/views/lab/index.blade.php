<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            실전관 - 샘플 만세력
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg text-sm">
                실제 사주 예시를 분석하면서 학습한 한자를 실전에서 확인해 보세요.
            </div>

            @if($examples->isEmpty())
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <div class="text-gray-400 text-lg">등록된 샘플 차트가 없습니다.</div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($examples as $example)
                    <a href="{{ route('lab.show', $example->slug) }}"
                       class="bg-white rounded-lg shadow hover:shadow-md transition-shadow block overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $example->title }}</h3>
                            @if($example->description)
                                <p class="text-sm text-gray-500 mb-4">{{ Str::limit($example->description, 80) }}</p>
                            @endif

                            {{-- Mini chart preview --}}
                            <div class="grid grid-cols-4 gap-2 text-center">
                                @foreach(['year' => '연', 'month' => '월', 'day' => '일', 'hour' => '시'] as $key => $label)
                                <div class="bg-gray-50 rounded p-2">
                                    <div class="text-xs text-gray-400 mb-1">{{ $label }}주</div>
                                    <div class="text-lg font-bold text-gray-800">{{ $example->{$key . '_stem'} }}</div>
                                    <div class="text-lg font-bold text-gray-600">{{ $example->{$key . '_branch'} }}</div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                                <span>{{ $example->gender === 'M' ? '남성' : ($example->gender === 'F' ? '여성' : '') }}</span>
                                <span>난이도 Lv.{{ $example->difficulty_level }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

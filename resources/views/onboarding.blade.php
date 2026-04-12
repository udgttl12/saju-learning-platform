<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            학습 프로필 설정
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm dark:shadow-slate-900/50 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <p class="mb-6 text-gray-600 dark:text-slate-300">
                        맞춤형 학습 경험을 위해 몇 가지 질문에 답해주세요.
                    </p>

                    <form method="POST" action="{{ route('onboarding.store') }}" id="onboarding-form">
                        @csrf

                        {{-- Step 1: 사주 경험 수준 --}}
                        <div id="step-1" class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">1단계: 사주 경험</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400">사주/명리학에 대해 얼마나 알고 계신가요?</p>

                            <div class="space-y-2">
                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="beginner_level" value="complete_beginner" class="mr-3 text-indigo-600" {{ old('beginner_level') === 'complete_beginner' ? 'checked' : '' }} required>
                                    <div>
                                        <span class="font-medium dark:text-white">완전 초보</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">사주가 무엇인지 잘 모릅니다</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="beginner_level" value="some_knowledge" class="mr-3 text-indigo-600" {{ old('beginner_level') === 'some_knowledge' ? 'checked' : '' }}>
                                    <div>
                                        <span class="font-medium dark:text-white">기초 지식 있음</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">천간/지지 정도는 들어봤습니다</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="beginner_level" value="intermediate" class="mr-3 text-indigo-600" {{ old('beginner_level') === 'intermediate' ? 'checked' : '' }}>
                                    <div>
                                        <span class="font-medium dark:text-white">중급</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">오행, 십성 등 기본 개념을 알고 있습니다</p>
                                    </div>
                                </label>
                            </div>

                            @error('beginner_level')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            <div class="flex justify-end pt-4">
                                <button type="button" onclick="showStep(2)" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                    다음
                                </button>
                            </div>
                        </div>

                        {{-- Step 2: 한자 경험 --}}
                        <div id="step-2" class="space-y-4 hidden">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">2단계: 한자 경험</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400">한자를 얼마나 읽을 수 있나요?</p>

                            <div class="space-y-2">
                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="hanja_level" value="none" class="mr-3 text-indigo-600" {{ old('hanja_level') === 'none' ? 'checked' : '' }} required>
                                    <div>
                                        <span class="font-medium dark:text-white">전혀 모름</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">한자를 거의 읽지 못합니다</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="hanja_level" value="basic" class="mr-3 text-indigo-600" {{ old('hanja_level') === 'basic' ? 'checked' : '' }}>
                                    <div>
                                        <span class="font-medium dark:text-white">기초</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">간단한 한자는 읽을 수 있습니다</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="hanja_level" value="intermediate" class="mr-3 text-indigo-600" {{ old('hanja_level') === 'intermediate' ? 'checked' : '' }}>
                                    <div>
                                        <span class="font-medium dark:text-white">중급</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">일반적인 한자를 읽을 수 있습니다</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    <input type="radio" name="hanja_level" value="advanced" class="mr-3 text-indigo-600" {{ old('hanja_level') === 'advanced' ? 'checked' : '' }}>
                                    <div>
                                        <span class="font-medium dark:text-white">고급</span>
                                        <p class="text-sm text-gray-500 dark:text-slate-400">한자에 익숙합니다</p>
                                    </div>
                                </label>
                            </div>

                            @error('hanja_level')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            <div class="flex justify-between pt-4">
                                <button type="button" onclick="showStep(1)" class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-md hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    이전
                                </button>
                                <button type="button" onclick="showStep(3)" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                    다음
                                </button>
                            </div>
                        </div>

                        {{-- Step 3: 학습 목표 및 스타일 --}}
                        <div id="step-3" class="space-y-6 hidden">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">3단계: 학습 설정</h3>

                            <div class="space-y-2">
                                <label for="daily_goal_minutes" class="block font-medium text-gray-700 dark:text-slate-300">하루 학습 목표 (분)</label>
                                <input type="range" name="daily_goal_minutes" id="daily_goal_minutes"
                                       min="5" max="120" step="5" value="{{ old('daily_goal_minutes', 15) }}"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                       oninput="document.getElementById('goal-display').textContent = this.value + '분'">
                                <p class="text-center text-lg font-semibold text-indigo-600" id="goal-display">{{ old('daily_goal_minutes', 15) }}분</p>
                            </div>

                            @error('daily_goal_minutes')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            <div class="space-y-2">
                                <p class="font-medium text-gray-700 dark:text-slate-300">선호하는 학습 방식</p>

                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex flex-col items-center p-4 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition text-center">
                                        <input type="radio" name="preferred_learning_style" value="visual" class="mb-2 text-indigo-600" {{ old('preferred_learning_style') === 'visual' ? 'checked' : '' }} required>
                                        <span class="font-medium dark:text-white">시각적 학습</span>
                                        <span class="text-xs text-gray-500 dark:text-slate-400">도표, 그림 위주</span>
                                    </label>

                                    <label class="flex flex-col items-center p-4 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition text-center">
                                        <input type="radio" name="preferred_learning_style" value="reading" class="mb-2 text-indigo-600" {{ old('preferred_learning_style') === 'reading' ? 'checked' : '' }}>
                                        <span class="font-medium dark:text-white">읽기 학습</span>
                                        <span class="text-xs text-gray-500 dark:text-slate-400">텍스트 설명 위주</span>
                                    </label>

                                    <label class="flex flex-col items-center p-4 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition text-center">
                                        <input type="radio" name="preferred_learning_style" value="practice" class="mb-2 text-indigo-600" {{ old('preferred_learning_style') === 'practice' ? 'checked' : '' }}>
                                        <span class="font-medium dark:text-white">실습 학습</span>
                                        <span class="text-xs text-gray-500 dark:text-slate-400">퀴즈, 연습문제 위주</span>
                                    </label>

                                    <label class="flex flex-col items-center p-4 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition text-center">
                                        <input type="radio" name="preferred_learning_style" value="mixed" class="mb-2 text-indigo-600" {{ old('preferred_learning_style') === 'mixed' ? 'checked' : '' }}>
                                        <span class="font-medium dark:text-white">혼합형</span>
                                        <span class="text-xs text-gray-500 dark:text-slate-400">다양한 방식 조합</span>
                                    </label>
                                </div>
                            </div>

                            @error('preferred_learning_style')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            <div class="flex justify-between pt-4">
                                <button type="button" onclick="showStep(2)" class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-md hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    이전
                                </button>
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">
                                    학습 시작하기
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Step Indicator --}}
                    <div class="flex justify-center mt-8 space-x-2" id="step-indicators">
                        <div class="w-3 h-3 rounded-full bg-indigo-600 step-dot" data-step="1"></div>
                        <div class="w-3 h-3 rounded-full bg-gray-300 step-dot" data-step="2"></div>
                        <div class="w-3 h-3 rounded-full bg-gray-300 step-dot" data-step="3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showStep(step) {
            document.querySelectorAll('[id^="step-"]').forEach(el => {
                if (el.id.match(/^step-\d+$/)) {
                    el.classList.add('hidden');
                }
            });
            document.getElementById('step-' + step).classList.remove('hidden');

            document.querySelectorAll('.step-dot').forEach(dot => {
                dot.classList.remove('bg-indigo-600');
                dot.classList.add('bg-gray-300');
            });
            document.querySelector('.step-dot[data-step="' + step + '"]').classList.remove('bg-gray-300');
            document.querySelector('.step-dot[data-step="' + step + '"]').classList.add('bg-indigo-600');
        }
    </script>
</x-app-layout>

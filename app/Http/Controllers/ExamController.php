<?php

namespace App\Http\Controllers;

use App\Models\HanjaChar;
use App\Models\ReviewCard;
use App\Services\TwelveShinsalQuestionGeneratorService;
use App\Services\YukchinQuestionGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private const BASE_COUNT_OPTIONS = [10, 20, 50, 100];

    public function __construct(
        private TwelveShinsalQuestionGeneratorService $twelveShinsalQuestionGeneratorService,
        private YukchinQuestionGeneratorService $yukchinQuestionGeneratorService,
    ) {}

    private function categoryLabels(): array
    {
        return [
            'all' => '전체 한자',
            'five_elements' => '오행',
            'heavenly_stems' => '천간',
            'earthly_branches' => '지지',
            'twelve_shinsal' => '12신살',
            'yukchin' => '육친론(십성)',
        ];
    }

    public function index()
    {
        $hanjaCounts = [
            'all' => HanjaChar::where('publish_status', 'published')->count(),
            'five_elements' => HanjaChar::where('publish_status', 'published')->where('category', 'five_elements')->count(),
            'heavenly_stems' => HanjaChar::where('publish_status', 'published')->where('category', 'heavenly_stems')->count(),
            'earthly_branches' => HanjaChar::where('publish_status', 'published')->where('category', 'earthly_branches')->count(),
        ];

        $generatedCounts = [
            'twelve_shinsal' => $this->twelveShinsalQuestionGeneratorService->getPoolSize(),
            'yukchin' => $this->yukchinQuestionGeneratorService->getPoolSize(),
        ];

        $labels = $this->categoryLabels();
        $categories = [];

        foreach (array_merge($hanjaCounts, $generatedCounts) as $key => $count) {
            $categories[$key] = [
                'label' => $labels[$key],
                'count' => $count,
            ];
        }

        return view('exam.index', [
            'categories' => $categories,
            'countOptions' => $this->buildCountOptions($categories),
        ]);
    }

    private function buildCountOptions(array $categories): array
    {
        $fallbackCounts = array_filter(
            array_column($categories, 'count'),
            fn (int $count) => $count >= 4 && $count < min(self::BASE_COUNT_OPTIONS),
        );

        $countOptions = array_values(array_unique(array_merge(self::BASE_COUNT_OPTIONS, $fallbackCounts)));
        sort($countOptions);

        return $countOptions;
    }

    public function start(Request $request)
    {
        $validCategories = array_keys($this->categoryLabels());

        $request->validate([
            'category' => 'required|in:'.implode(',', $validCategories),
            'count' => 'required|integer|min:4',
        ]);

        $category = $request->string('category')->value();
        $requestedCount = (int) $request->input('count');

        [$examData, $sourceSize] = match ($category) {
            'twelve_shinsal' => [
                $this->twelveShinsalQuestionGeneratorService->buildExamQuestions($requestedCount),
                $this->twelveShinsalQuestionGeneratorService->getPoolSize(),
            ],
            'yukchin' => [
                $this->yukchinQuestionGeneratorService->buildExamQuestions($requestedCount),
                $this->yukchinQuestionGeneratorService->getPoolSize(),
            ],
            default => $this->buildHanjaExam($category, $requestedCount),
        };

        if ($sourceSize < 4 || empty($examData)) {
            return back()->with('error', '문제를 만들기에 자료가 부족합니다.');
        }

        session()->put('exam_data', [
            'category' => $category,
            'questions' => $examData,
            'requested_count' => $requestedCount,
            'actual_count' => count($examData),
            'source_size' => $sourceSize,
            'started_at' => now()->toISOString(),
        ]);

        return redirect()->route('exam.play');
    }

    private function buildHanjaExam(string $category, int $requestedCount): array
    {
        $query = HanjaChar::where('publish_status', 'published');

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $allChars = $query->get();
        $sourceSize = $allChars->count();

        if ($sourceSize < 4) {
            return [null, $sourceSize];
        }

        $questions = $allChars->shuffle()->take(min($requestedCount, $sourceSize));
        $examData = [];

        foreach ($questions as $char) {
            $wrongPool = $allChars
                ->where('id', '!=', $char->id)
                ->shuffle()
                ->take(3);

            if ($wrongPool->count() < 3) {
                $extraPool = HanjaChar::where('publish_status', 'published')
                    ->where('id', '!=', $char->id)
                    ->inRandomOrder()
                    ->limit(3 - $wrongPool->count())
                    ->get();

                $wrongPool = $wrongPool->merge($extraPool)->take(3);
            }

            $choices = $wrongPool
                ->map(fn ($candidate) => [
                    'id' => $candidate->id,
                    'text' => $candidate->meaning_ko.' ('.$candidate->reading_ko.')',
                ])
                ->push([
                    'id' => $char->id,
                    'text' => $char->meaning_ko.' ('.$char->reading_ko.')',
                ])
                ->shuffle()
                ->values()
                ->all();

            $examData[] = [
                'hanja_char_id' => $char->id,
                'has_char' => true,
                'char_value' => $char->char_value,
                'reading_ko' => $char->reading_ko,
                'meaning_ko' => $char->meaning_ko,
                'element' => $char->element,
                'prompt' => '이 한자의 뜻은 무엇일까요?',
                'correct_id' => $char->id,
                'choices' => $choices,
                'explanation' => null,
            ];
        }

        return [$examData, $sourceSize];
    }

    public function play()
    {
        $data = session('exam_data');

        if (! $data) {
            return redirect()->route('exam.index');
        }

        $labels = $this->categoryLabels();

        return view('exam.play', [
            'questions' => $data['questions'],
            'category' => $data['category'],
            'categoryLabel' => $labels[$data['category']] ?? $data['category'],
            'requestedCount' => $data['requested_count'] ?? count($data['questions']),
            'actualCount' => $data['actual_count'] ?? count($data['questions']),
        ]);
    }

    public function submit(Request $request)
    {
        $data = session('exam_data');

        if (! $data) {
            return redirect()->route('exam.index');
        }

        $answers = $request->input('answers', []);
        $results = [];
        $correctCount = 0;

        foreach ($data['questions'] as $index => $question) {
            $userAnswer = (int) ($answers[$index] ?? 0);
            $isCorrect = $userAnswer === $question['correct_id'];

            if ($isCorrect) {
                $correctCount++;
            }

            $results[] = array_merge($question, [
                'user_answer_id' => $userAnswer,
                'is_correct' => $isCorrect,
            ]);
        }

        $total = count($results);
        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        if ($request->user()) {
            foreach ($results as $result) {
                if ($result['is_correct'] || empty($result['hanja_char_id'])) {
                    continue;
                }

                $card = ReviewCard::where('user_id', $request->user()->id)
                    ->where('hanja_char_id', $result['hanja_char_id'])
                    ->first();

                if ($card) {
                    $card->update([
                        'due_at' => Carbon::now(),
                        'stage' => $card->stage === 'mastered' ? 'lapsed' : $card->stage,
                    ]);

                    continue;
                }

                ReviewCard::create([
                    'user_id' => $request->user()->id,
                    'hanja_char_id' => $result['hanja_char_id'],
                    'source_type' => 'exam',
                    'stage' => 'new',
                    'ease_factor' => 2.50,
                    'interval_days' => 0,
                    'repetitions' => 0,
                    'due_at' => Carbon::now(),
                ]);
            }
        }

        $labels = $this->categoryLabels();
        $requestedCount = $data['requested_count'] ?? $total;

        session()->forget('exam_data');

        return view('exam.result', [
            'results' => $results,
            'correctCount' => $correctCount,
            'total' => $total,
            'score' => $score,
            'categoryLabel' => $labels[$data['category']] ?? '',
            'requestedCount' => $requestedCount,
            'isHanjaCategory' => in_array($data['category'], ['all', 'five_elements', 'heavenly_stems', 'earthly_branches'], true),
        ]);
    }
}

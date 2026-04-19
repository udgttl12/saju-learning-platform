<?php

namespace App\Http\Controllers;

use App\Models\HanjaChar;
use App\Models\ReviewCard;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    private const COUNT_OPTIONS = [5, 10, 15, 20, 27];

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

        $quizCounts = [
            'twelve_shinsal' => \App\Models\QuizItem::whereHas('quizSet', fn($q) => $q->where('code', 'EXAM_TWELVE_SHINSAL'))->count(),
            'yukchin' => \App\Models\QuizItem::whereHas('quizSet', fn($q) => $q->where('code', 'EXAM_YUKCHIN'))->count(),
        ];

        $labels = $this->categoryLabels();
        $categories = [];
        foreach (array_merge($hanjaCounts, $quizCounts) as $key => $count) {
            $categories[$key] = ['label' => $labels[$key], 'count' => $count];
        }

        return view('exam.index', [
            'categories' => $categories,
            'countOptions' => self::COUNT_OPTIONS,
        ]);
    }

    public function start(Request $request)
    {
        $validCategories = array_keys($this->categoryLabels());
        $request->validate([
            'category' => 'required|in:' . implode(',', $validCategories),
            'count' => 'required|integer|min:5|max:30',
        ]);

        $category = $request->category;
        $requestedCount = (int) $request->count;

        [$examData, $sourceSize] = in_array($category, ['twelve_shinsal', 'yukchin'], true)
            ? $this->buildQuizSetExam($category, $requestedCount)
            : $this->buildHanjaExam($category, $requestedCount);

        if ($examData === null) {
            return back()->with('error', '문제를 만들기에 자료가 부족합니다.');
        }

        $actualCount = count($examData);

        session()->put('exam_data', [
            'category' => $category,
            'questions' => $examData,
            'requested_count' => $requestedCount,
            'actual_count' => $actualCount,
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

        $count = min($requestedCount, $sourceSize);
        $questions = $allChars->shuffle()->take($count);
        $examData = [];

        foreach ($questions as $char) {
            $wrongPool = $allChars->where('id', '!=', $char->id)->shuffle()->take(3);
            if ($wrongPool->count() < 3) {
                $extraPool = HanjaChar::where('publish_status', 'published')
                    ->where('id', '!=', $char->id)
                    ->inRandomOrder()
                    ->limit(3 - $wrongPool->count())
                    ->get();
                $wrongPool = $wrongPool->merge($extraPool)->take(3);
            }

            $choices = $wrongPool->map(fn($c) => [
                'id' => $c->id,
                'text' => $c->meaning_ko . ' (' . $c->reading_ko . ')',
            ])->push([
                'id' => $char->id,
                'text' => $char->meaning_ko . ' (' . $char->reading_ko . ')',
            ])->shuffle()->values()->all();

            $examData[] = [
                'hanja_char_id' => $char->id,
                'has_char' => true,
                'char_value' => $char->char_value,
                'reading_ko' => $char->reading_ko,
                'meaning_ko' => $char->meaning_ko,
                'element' => $char->element,
                'prompt' => '이 한자의 뜻은?',
                'correct_id' => $char->id,
                'choices' => $choices,
                'explanation' => null,
            ];
        }

        return [$examData, $sourceSize];
    }

    private function buildQuizSetExam(string $category, int $requestedCount): array
    {
        $setCode = $category === 'twelve_shinsal' ? 'EXAM_TWELVE_SHINSAL' : 'EXAM_YUKCHIN';

        $set = \App\Models\QuizSet::where('code', $setCode)->first();
        if (!$set) {
            return [null, 0];
        }

        $items = \App\Models\QuizItem::where('quiz_set_id', $set->id)->get();
        $sourceSize = $items->count();

        if ($sourceSize < 4) {
            return [null, $sourceSize];
        }

        $count = min($requestedCount, $sourceSize);
        $selected = $items->shuffle()->take($count);
        $examData = [];

        foreach ($selected as $item) {
            $choicesRaw = is_array($item->choices_json) ? $item->choices_json : (json_decode($item->choices_json, true) ?: []);
            $answer = is_array($item->answer_payload_json) ? $item->answer_payload_json : (json_decode($item->answer_payload_json, true) ?: []);
            $correctIdx = (int) ($answer['correct_choice_index'] ?? 0);

            $choices = [];
            foreach ($choicesRaw as $idx => $text) {
                $choices[] = ['id' => $item->id * 100 + $idx, 'text' => $text];
            }
            $correctId = $item->id * 100 + $correctIdx;
            shuffle($choices);

            $prompt = $item->prompt_text;
            $displayLabel = $choicesRaw[$correctIdx] ?? '';

            $examData[] = [
                'hanja_char_id' => null,
                'has_char' => false,
                'char_value' => '',
                'reading_ko' => '',
                'meaning_ko' => $displayLabel,
                'element' => null,
                'prompt' => $prompt,
                'correct_id' => $correctId,
                'choices' => $choices,
                'explanation' => $item->explanation_text,
            ];
        }

        return [$examData, $sourceSize];
    }

    public function play()
    {
        $data = session('exam_data');
        if (!$data) {
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
        if (!$data) {
            return redirect()->route('exam.index');
        }

        $answers = $request->input('answers', []);
        $results = [];
        $correctCount = 0;

        foreach ($data['questions'] as $i => $q) {
            $userAnswer = (int) ($answers[$i] ?? 0);
            $isCorrect = $userAnswer === $q['correct_id'];
            if ($isCorrect) $correctCount++;

            $results[] = array_merge($q, [
                'user_answer_id' => $userAnswer,
                'is_correct' => $isCorrect,
            ]);
        }

        $total = count($results);
        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        // 오답 한자를 복습 카드에 등록
        if ($request->user()) {
            foreach ($results as $r) {
                if ($r['is_correct'] || empty($r['hanja_char_id'])) continue;

                $card = ReviewCard::where('user_id', $request->user()->id)
                    ->where('hanja_char_id', $r['hanja_char_id'])
                    ->first();

                if ($card) {
                    $card->update(['due_at' => Carbon::now(), 'stage' => $card->stage === 'mastered' ? 'lapsed' : $card->stage]);
                } else {
                    ReviewCard::create([
                        'user_id' => $request->user()->id,
                        'hanja_char_id' => $r['hanja_char_id'],
                        'source_type' => 'exam',
                        'stage' => 'new',
                        'ease_factor' => 2.50,
                        'interval_days' => 0,
                        'repetitions' => 0,
                        'due_at' => Carbon::now(),
                    ]);
                }
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

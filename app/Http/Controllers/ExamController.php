<?php

namespace App\Http\Controllers;

use App\Models\HanjaChar;
use App\Models\ReviewCard;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function index()
    {
        $categories = [
            'all' => ['label' => '전체', 'count' => HanjaChar::where('publish_status', 'published')->count()],
            'five_elements' => ['label' => '오행', 'count' => HanjaChar::where('publish_status', 'published')->where('category', 'five_elements')->count()],
            'heavenly_stems' => ['label' => '천간', 'count' => HanjaChar::where('publish_status', 'published')->where('category', 'heavenly_stems')->count()],
            'earthly_branches' => ['label' => '지지', 'count' => HanjaChar::where('publish_status', 'published')->where('category', 'earthly_branches')->count()],
        ];

        return view('exam.index', compact('categories'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'category' => 'required|in:all,five_elements,heavenly_stems,earthly_branches',
            'count' => 'required|integer|min:5|max:30',
        ]);

        $category = $request->category;
        $count = (int) $request->count;

        $query = HanjaChar::where('publish_status', 'published');
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $allChars = $query->get();

        if ($allChars->count() < 4) {
            return back()->with('error', '문제를 만들기에 한자가 부족합니다.');
        }

        $questions = $allChars->shuffle()->take($count);
        $examData = [];

        foreach ($questions as $char) {
            // 오답 선택지 3개 (같은 카테고리 우선, 부족하면 전체에서)
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
                'char_value' => $char->char_value,
                'reading_ko' => $char->reading_ko,
                'meaning_ko' => $char->meaning_ko,
                'element' => $char->element,
                'correct_id' => $char->id,
                'choices' => $choices,
            ];
        }

        session()->put('exam_data', [
            'category' => $category,
            'questions' => $examData,
            'started_at' => now()->toISOString(),
        ]);

        return redirect()->route('exam.play');
    }

    public function play()
    {
        $data = session('exam_data');
        if (!$data) {
            return redirect()->route('exam.index');
        }

        $categoryLabels = [
            'all' => '전체',
            'five_elements' => '오행',
            'heavenly_stems' => '천간',
            'earthly_branches' => '지지',
        ];

        return view('exam.play', [
            'questions' => $data['questions'],
            'category' => $data['category'],
            'categoryLabel' => $categoryLabels[$data['category']] ?? $data['category'],
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

        session()->forget('exam_data');

        return view('exam.result', [
            'results' => $results,
            'correctCount' => $correctCount,
            'total' => $total,
            'score' => $score,
            'categoryLabel' => ['all' => '전체', 'five_elements' => '오행', 'heavenly_stems' => '천간', 'earthly_branches' => '지지'][$data['category']] ?? '',
        ]);
    }
}

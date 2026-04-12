<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        private QuizService $quizService
    ) {}

    public function show(string $code)
    {
        $quizSet = $this->quizService->getQuizSetByCode($code);

        return view('quiz.show', compact('quizSet'));
    }

    public function submit(string $code, Request $request)
    {
        $quizSet = $this->quizService->getQuizSetByCode($code);

        $request->validate([
            'answers' => 'required|array',
        ]);

        $results = $this->quizService->gradeSubmission($quizSet, $request->answers);
        $score = $this->quizService->calculateScore($results);

        session()->put("quiz_result_{$code}", [
            'results' => $results,
            'score' => $score,
            'quiz_set' => $quizSet,
        ]);

        return redirect()->route('quiz.result', $code);
    }

    public function result(string $code)
    {
        $data = session("quiz_result_{$code}");

        if (!$data) {
            return redirect()->route('quiz.show', $code);
        }

        return view('quiz.result', [
            'results' => $data['results'],
            'score' => $data['score'],
            'quizSet' => $data['quiz_set'],
        ]);
    }
}

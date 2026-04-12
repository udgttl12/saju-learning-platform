<?php

namespace App\Http\Controllers;

use App\Models\ReviewCard;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService
    ) {}

    public function index()
    {
        $cards = $this->reviewService->getDueCards(auth()->user());

        return view('review.index', compact('cards'));
    }

    public function show(int $id)
    {
        $card = ReviewCard::where('user_id', auth()->id())
            ->with('hanjaChar')
            ->findOrFail($id);

        return view('review.show', compact('card'));
    }

    public function answer(int $id, Request $request)
    {
        $request->validate([
            'result' => 'required|string|in:again,hard,good,easy',
        ]);

        $card = ReviewCard::where('user_id', auth()->id())
            ->findOrFail($id);

        $this->reviewService->processAnswer($card, $request->result);

        return redirect()->route('review.index')
            ->with('success', '복습 결과가 저장되었습니다.');
    }
}

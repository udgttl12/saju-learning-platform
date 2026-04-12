<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(): View
    {
        return view('onboarding');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'beginner_level' => ['required', 'string', 'in:complete_beginner,some_knowledge,intermediate'],
            'hanja_level' => ['required', 'string', 'in:none,basic,intermediate,advanced'],
            'daily_goal_minutes' => ['required', 'integer', 'min:5', 'max:120'],
            'preferred_learning_style' => ['required', 'string', 'in:visual,reading,practice,mixed'],
        ]);

        $profile = $request->user()->profile;

        $profile->update([
            ...$validated,
            'onboarding_completed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('status', '온보딩이 완료되었습니다!');
    }
}

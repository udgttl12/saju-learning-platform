<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuestSessionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $redirectTo = $this->resolveRedirectTo($request->string('redirect_to')->toString());

        $user = User::create([
            'email' => 'guest+'.Str::lower((string) Str::ulid()).'@guest.local',
            'password' => Str::random(32),
            'email_verified_at' => now(),
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $user->profile()->create([
            'display_name' => '게스트 사용자',
            'beginner_level' => 'absolute_beginner',
            'hanja_level' => 'none',
            'daily_goal_minutes' => 10,
            'preferred_learning_style' => 'balanced',
            'timezone' => 'Asia/Seoul',
            'onboarding_completed_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to($redirectTo)
            ->with('success', '게스트 계정으로 바로 시작했어요. 이 계정에서는 진도와 북마크를 저장할 수 있어요.');
    }

    private function resolveRedirectTo(string $redirectTo): string
    {
        if ($redirectTo !== '' && str_starts_with($redirectTo, '/')) {
            return $redirectTo;
        }

        return route('tracks.index', absolute: false);
    }
}

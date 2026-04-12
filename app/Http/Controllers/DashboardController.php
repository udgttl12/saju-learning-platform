<?php

namespace App\Http\Controllers;

use App\Models\ReviewCard;
use App\Models\TrackEnrollment;
use App\Models\LessonAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 등록된 트랙 + 진행률
        $enrollments = TrackEnrollment::where('user_id', $user->id)
            ->with('learningTrack')
            ->orderByDesc('last_accessed_at')
            ->get();

        // 복습 대기 카드 수
        $reviewDueCount = ReviewCard::where('user_id', $user->id)
            ->where('due_at', '<=', now())
            ->count();

        // 최근 레슨 (최근 접근한 LessonAttempt)
        $recentAttempts = LessonAttempt::where('user_id', $user->id)
            ->with('lesson.learningTrack')
            ->orderByDesc('last_accessed_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('enrollments', 'reviewDueCount', 'recentAttempts'));
    }
}

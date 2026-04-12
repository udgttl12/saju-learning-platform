<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\HanjaChar;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        // 대상 객체를 수동으로 로드 (polymorphic 미사용)
        $bookmarks->each(function ($bookmark) {
            if ($bookmark->target_type === 'hanja_char') {
                $bookmark->target = HanjaChar::find($bookmark->target_id);
            } elseif ($bookmark->target_type === 'lesson') {
                $bookmark->target = Lesson::find($bookmark->target_id);
            }
        });

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:hanja_char,lesson',
            'target_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $existing = Bookmark::where('user_id', $user->id)
            ->where('target_type', $request->target_type)
            ->where('target_id', $request->target_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'target_type' => $request->target_type,
                'target_id' => $request->target_id,
                'created_at' => now(),
            ]);
            $status = 'added';
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? '즐겨찾기에 추가했습니다.' : '즐겨찾기에서 제거했습니다.');
    }
}

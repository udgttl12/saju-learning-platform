<?php

namespace App\Http\Controllers;

use App\Models\HanjaChar;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HanjaCharController extends Controller
{
    public function show(string $slug)
    {
        $hanja = HanjaChar::where('slug', $slug)
            ->where('publish_status', 'published')
            ->with(['lessons', 'groups'])
            ->firstOrFail();

        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = Bookmark::where('user_id', Auth::id())
                ->where('target_type', 'hanja_char')
                ->where('target_id', $hanja->id)
                ->exists();
        }

        return view('hanja.show', compact('hanja', 'isBookmarked'));
    }
}

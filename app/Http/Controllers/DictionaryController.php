<?php

namespace App\Http\Controllers;

use App\Models\HanjaChar;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category', 'all');

        $query = HanjaChar::where('publish_status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('char_value', 'like', "%{$search}%")
                  ->orWhere('reading_ko', 'like', "%{$search}%")
                  ->orWhere('meaning_ko', 'like', "%{$search}%");
            });
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if ($request->filled('element')) {
            $query->where('element', $request->element);
        }

        $hanjaChars = $query->orderBy('id')->paginate(60)->withQueryString();

        $categoryCounts = HanjaChar::where('publish_status', 'published')
            ->selectRaw('category, count(*) as cnt')
            ->groupBy('category')
            ->pluck('cnt', 'category');

        $totalCount = HanjaChar::where('publish_status', 'published')->count();

        return view('dictionary.index', compact('hanjaChars', 'category', 'categoryCounts', 'totalCount'));
    }
}

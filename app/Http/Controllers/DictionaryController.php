<?php

namespace App\Http\Controllers;

use App\Models\HanjaChar;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function index(Request $request)
    {
        $query = HanjaChar::where('publish_status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('char_value', 'like', "%{$search}%")
                  ->orWhere('reading_ko', 'like', "%{$search}%")
                  ->orWhere('meaning_ko', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('element')) {
            $query->where('element', $request->element);
        }

        $hanjaChars = $query->orderBy('reading_ko')->paginate(24)->withQueryString();
        $categories = HanjaChar::where('publish_status', 'published')
            ->select('category')->distinct()->whereNotNull('category')->pluck('category');
        $elements = HanjaChar::where('publish_status', 'published')
            ->select('element')->distinct()->whereNotNull('element')->pluck('element');

        return view('dictionary.index', compact('hanjaChars', 'categories', 'elements'));
    }
}

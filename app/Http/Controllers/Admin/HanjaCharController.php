<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\HanjaChar;
use Illuminate\Http\Request;

class HanjaCharController extends Controller
{
    public function index(Request $request)
    {
        $query = HanjaChar::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('char_value', 'like', "%{$search}%")
                  ->orWhere('reading_ko', 'like', "%{$search}%")
                  ->orWhere('meaning_ko', 'like', "%{$search}%");
            });
        }

        $hanjaChars = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $categories = HanjaChar::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('admin.hanja-chars.index', compact('hanjaChars', 'categories'));
    }

    public function create()
    {
        return view('admin.hanja-chars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'char_value' => 'required|string|max:4',
            'slug' => 'required|string|max:100|unique:hanja_chars,slug',
            'reading_ko' => 'required|string|max:50',
            'meaning_ko' => 'required|string|max:200',
            'category' => 'nullable|string|max:50',
            'element' => 'nullable|string|max:10',
            'yin_yang' => 'nullable|string|max:10',
            'structure_note' => 'nullable|string',
            'mnemonic_text' => 'nullable|string',
            'usage_in_saju' => 'nullable|string',
            'stroke_count' => 'nullable|integer|min:1',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $hanjaChar = HanjaChar::create($validated);

        $this->logAudit('hanja_chars', $hanjaChar->id, 'create', $validated);

        return redirect()->route('admin.hanja-chars.index')
            ->with('success', '한자가 등록되었습니다.');
    }

    public function edit(HanjaChar $hanjaChar)
    {
        return view('admin.hanja-chars.edit', compact('hanjaChar'));
    }

    public function update(Request $request, HanjaChar $hanjaChar)
    {
        $validated = $request->validate([
            'char_value' => 'required|string|max:4',
            'slug' => 'required|string|max:100|unique:hanja_chars,slug,' . $hanjaChar->id,
            'reading_ko' => 'required|string|max:50',
            'meaning_ko' => 'required|string|max:200',
            'category' => 'nullable|string|max:50',
            'element' => 'nullable|string|max:10',
            'yin_yang' => 'nullable|string|max:10',
            'structure_note' => 'nullable|string',
            'mnemonic_text' => 'nullable|string',
            'usage_in_saju' => 'nullable|string',
            'stroke_count' => 'nullable|integer|min:1',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $old = $hanjaChar->toArray();
        $hanjaChar->update($validated);

        $this->logAudit('hanja_chars', $hanjaChar->id, 'update', ['old' => $old, 'new' => $validated]);

        return redirect()->route('admin.hanja-chars.index')
            ->with('success', '한자가 수정되었습니다.');
    }

    public function destroy(HanjaChar $hanjaChar)
    {
        $this->logAudit('hanja_chars', $hanjaChar->id, 'delete', ['deleted' => $hanjaChar->toArray()]);
        $hanjaChar->delete();

        return redirect()->route('admin.hanja-chars.index')
            ->with('success', '한자가 삭제되었습니다.');
    }

    private function logAudit(string $entityType, int $entityId, string $actionType, array $diff): void
    {
        AdminAuditLog::create([
            'admin_user_id' => auth()->id(),
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action_type' => $actionType,
            'diff_json' => $diff,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}

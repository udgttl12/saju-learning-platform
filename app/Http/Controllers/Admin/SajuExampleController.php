<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\SajuExample;
use Illuminate\Http\Request;

class SajuExampleController extends Controller
{
    public function index()
    {
        $examples = SajuExample::orderBy('id', 'desc')->paginate(20);
        return view('admin.saju-examples.index', compact('examples'));
    }

    public function create()
    {
        return view('admin.saju-examples.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:saju_examples,code',
            'slug' => 'required|string|max:100|unique:saju_examples,slug',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'gender' => 'nullable|string|in:M,F',
            'solar_birth_datetime' => 'nullable|date',
            'lunar_birth_label' => 'nullable|string|max:100',
            'year_stem' => 'nullable|string|max:4',
            'year_branch' => 'nullable|string|max:4',
            'month_stem' => 'nullable|string|max:4',
            'month_branch' => 'nullable|string|max:4',
            'day_stem' => 'nullable|string|max:4',
            'day_branch' => 'nullable|string|max:4',
            'hour_stem' => 'nullable|string|max:4',
            'hour_branch' => 'nullable|string|max:4',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $example = SajuExample::create($validated);

        $this->logAudit('saju_examples', $example->id, 'create', $validated);

        return redirect()->route('admin.saju-examples.index')
            ->with('success', '사주 예시가 등록되었습니다.');
    }

    public function edit(SajuExample $sajuExample)
    {
        return view('admin.saju-examples.edit', compact('sajuExample'));
    }

    public function update(Request $request, SajuExample $sajuExample)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:saju_examples,code,' . $sajuExample->id,
            'slug' => 'required|string|max:100|unique:saju_examples,slug,' . $sajuExample->id,
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'gender' => 'nullable|string|in:M,F',
            'solar_birth_datetime' => 'nullable|date',
            'lunar_birth_label' => 'nullable|string|max:100',
            'year_stem' => 'nullable|string|max:4',
            'year_branch' => 'nullable|string|max:4',
            'month_stem' => 'nullable|string|max:4',
            'month_branch' => 'nullable|string|max:4',
            'day_stem' => 'nullable|string|max:4',
            'day_branch' => 'nullable|string|max:4',
            'hour_stem' => 'nullable|string|max:4',
            'hour_branch' => 'nullable|string|max:4',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $old = $sajuExample->toArray();
        $sajuExample->update($validated);

        $this->logAudit('saju_examples', $sajuExample->id, 'update', ['old' => $old, 'new' => $validated]);

        return redirect()->route('admin.saju-examples.index')
            ->with('success', '사주 예시가 수정되었습니다.');
    }

    public function destroy(SajuExample $sajuExample)
    {
        $this->logAudit('saju_examples', $sajuExample->id, 'delete', ['deleted' => $sajuExample->toArray()]);
        $sajuExample->delete();

        return redirect()->route('admin.saju-examples.index')
            ->with('success', '사주 예시가 삭제되었습니다.');
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

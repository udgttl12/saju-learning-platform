<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\LearningTrack;
use Illuminate\Http\Request;

class LearningTrackController extends Controller
{
    public function index()
    {
        $tracks = LearningTrack::withCount('lessons')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.learning-tracks.index', compact('tracks'));
    }

    public function create()
    {
        return view('admin.learning-tracks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:learning_tracks,code',
            'slug' => 'required|string|max:100|unique:learning_tracks,slug',
            'title' => 'required|string|max:200',
            'short_description' => 'nullable|string',
            'target_audience' => 'nullable|string|max:100',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'estimated_total_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'unlock_rule_json' => 'nullable|json',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $validated['unlock_rule_json'] = ! empty($validated['unlock_rule_json'])
            ? json_decode($validated['unlock_rule_json'], true)
            : null;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $track = LearningTrack::create($validated);

        $this->logAudit('learning_tracks', $track->id, 'create', $validated);

        return redirect()->route('admin.learning-tracks.index')
            ->with('success', '학습 트랙이 등록되었습니다.');
    }

    public function edit(LearningTrack $learningTrack)
    {
        return view('admin.learning-tracks.edit', compact('learningTrack'));
    }

    public function update(Request $request, LearningTrack $learningTrack)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:learning_tracks,code,'.$learningTrack->id,
            'slug' => 'required|string|max:100|unique:learning_tracks,slug,'.$learningTrack->id,
            'title' => 'required|string|max:200',
            'short_description' => 'nullable|string',
            'target_audience' => 'nullable|string|max:100',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'estimated_total_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'unlock_rule_json' => 'nullable|json',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $validated['unlock_rule_json'] = ! empty($validated['unlock_rule_json'])
            ? json_decode($validated['unlock_rule_json'], true)
            : null;
        $validated['updated_by'] = auth()->id();

        $old = $learningTrack->toArray();
        $learningTrack->update($validated);

        $this->logAudit('learning_tracks', $learningTrack->id, 'update', ['old' => $old, 'new' => $validated]);

        return redirect()->route('admin.learning-tracks.index')
            ->with('success', '학습 트랙이 수정되었습니다.');
    }

    public function destroy(LearningTrack $learningTrack)
    {
        $this->logAudit('learning_tracks', $learningTrack->id, 'delete', ['deleted' => $learningTrack->toArray()]);
        $learningTrack->delete();

        return redirect()->route('admin.learning-tracks.index')
            ->with('success', '학습 트랙이 삭제되었습니다.');
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

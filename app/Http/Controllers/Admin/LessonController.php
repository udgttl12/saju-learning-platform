<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\LearningTrack;
use App\Models\Lesson;
use App\Models\LessonStep;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with('learningTrack');

        if ($request->filled('track_id')) {
            $query->where('learning_track_id', $request->track_id);
        }

        $lessons = $query->orderBy('sort_order')->paginate(20)->withQueryString();
        $tracks = LearningTrack::orderBy('title')->get();

        return view('admin.lessons.index', compact('lessons', 'tracks'));
    }

    public function create()
    {
        $tracks = LearningTrack::orderBy('title')->get();
        return view('admin.lessons.create', compact('tracks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'learning_track_id' => 'required|exists:learning_tracks,id',
            'code' => 'required|string|max:30|unique:lessons,code',
            'slug' => 'required|string|max:100|unique:lessons,slug',
            'title' => 'required|string|max:200',
            'objective' => 'nullable|string',
            'summary' => 'nullable|string',
            'lesson_type' => 'required|string|max:30',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'estimated_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'publish_status' => 'required|string|in:draft,published,archived',
            // Lesson steps
            'steps' => 'nullable|array',
            'steps.*.step_type' => 'required_with:steps|string|max:30',
            'steps.*.title' => 'required_with:steps|string|max:200',
            'steps.*.content_markdown' => 'nullable|string',
            'steps.*.sort_order' => 'nullable|integer',
            'steps.*.is_required' => 'nullable|boolean',
            'steps.*.estimated_minutes' => 'nullable|integer',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $steps = $validated['steps'] ?? [];
        unset($validated['steps']);

        $lesson = Lesson::create($validated);

        foreach ($steps as $stepData) {
            $lesson->steps()->create($stepData);
        }

        $this->logAudit('lessons', $lesson->id, 'create', $validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', '레슨이 등록되었습니다.');
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load('steps');
        $tracks = LearningTrack::orderBy('title')->get();
        return view('admin.lessons.edit', compact('lesson', 'tracks'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'learning_track_id' => 'required|exists:learning_tracks,id',
            'code' => 'required|string|max:30|unique:lessons,code,' . $lesson->id,
            'slug' => 'required|string|max:100|unique:lessons,slug,' . $lesson->id,
            'title' => 'required|string|max:200',
            'objective' => 'nullable|string',
            'summary' => 'nullable|string',
            'lesson_type' => 'required|string|max:30',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'estimated_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'publish_status' => 'required|string|in:draft,published,archived',
            'steps' => 'nullable|array',
            'steps.*.id' => 'nullable|integer',
            'steps.*.step_type' => 'required_with:steps|string|max:30',
            'steps.*.title' => 'required_with:steps|string|max:200',
            'steps.*.content_markdown' => 'nullable|string',
            'steps.*.sort_order' => 'nullable|integer',
            'steps.*.is_required' => 'nullable|boolean',
            'steps.*.estimated_minutes' => 'nullable|integer',
        ]);

        $validated['updated_by'] = auth()->id();

        $steps = $validated['steps'] ?? [];
        unset($validated['steps']);

        $old = $lesson->toArray();
        $lesson->update($validated);

        // Sync steps
        $existingIds = [];
        foreach ($steps as $stepData) {
            if (!empty($stepData['id'])) {
                $step = LessonStep::find($stepData['id']);
                if ($step && $step->lesson_id === $lesson->id) {
                    $step->update($stepData);
                    $existingIds[] = $step->id;
                }
            } else {
                $newStep = $lesson->steps()->create($stepData);
                $existingIds[] = $newStep->id;
            }
        }

        // Remove deleted steps
        $lesson->steps()->whereNotIn('id', $existingIds)->delete();

        $this->logAudit('lessons', $lesson->id, 'update', ['old' => $old, 'new' => $validated]);

        return redirect()->route('admin.lessons.index')
            ->with('success', '레슨이 수정되었습니다.');
    }

    public function destroy(Lesson $lesson)
    {
        $this->logAudit('lessons', $lesson->id, 'delete', ['deleted' => $lesson->toArray()]);
        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', '레슨이 삭제되었습니다.');
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

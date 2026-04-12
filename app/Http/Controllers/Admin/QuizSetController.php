<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Lesson;
use App\Models\QuizSet;
use Illuminate\Http\Request;

class QuizSetController extends Controller
{
    public function index()
    {
        $quizSets = QuizSet::with('lesson')
            ->withCount('items')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.quiz-sets.index', compact('quizSets'));
    }

    public function create()
    {
        $lessons = Lesson::orderBy('title')->get();
        return view('admin.quiz-sets.create', compact('lessons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'nullable|exists:lessons,id',
            'code' => 'required|string|max:30|unique:quiz_sets,code',
            'title' => 'required|string|max:200',
            'scope_type' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'pass_score' => 'required|integer|min:0|max:100',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $quizSet = QuizSet::create($validated);

        $this->logAudit('quiz_sets', $quizSet->id, 'create', $validated);

        return redirect()->route('admin.quiz-sets.index')
            ->with('success', '퀴즈 세트가 등록되었습니다.');
    }

    public function edit(QuizSet $quizSet)
    {
        $quizSet->load('items');
        $lessons = Lesson::orderBy('title')->get();
        return view('admin.quiz-sets.edit', compact('quizSet', 'lessons'));
    }

    public function update(Request $request, QuizSet $quizSet)
    {
        $validated = $request->validate([
            'lesson_id' => 'nullable|exists:lessons,id',
            'code' => 'required|string|max:30|unique:quiz_sets,code,' . $quizSet->id,
            'title' => 'required|string|max:200',
            'scope_type' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'pass_score' => 'required|integer|min:0|max:100',
            'publish_status' => 'required|string|in:draft,published,archived',
        ]);

        $old = $quizSet->toArray();
        $quizSet->update($validated);

        $this->logAudit('quiz_sets', $quizSet->id, 'update', ['old' => $old, 'new' => $validated]);

        return redirect()->route('admin.quiz-sets.index')
            ->with('success', '퀴즈 세트가 수정되었습니다.');
    }

    public function destroy(QuizSet $quizSet)
    {
        $this->logAudit('quiz_sets', $quizSet->id, 'delete', ['deleted' => $quizSet->toArray()]);
        $quizSet->delete();

        return redirect()->route('admin.quiz-sets.index')
            ->with('success', '퀴즈 세트가 삭제되었습니다.');
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

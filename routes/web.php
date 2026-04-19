<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HanjaCharController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
Route::get('/tracks/{slug}', [TrackController::class, 'show'])->name('tracks.show');

Route::get('/hanja/{slug}', [HanjaCharController::class, 'show'])->name('hanja.show');
Route::get('/lessons/{slug}', [LessonController::class, 'show'])->name('lessons.show');
Route::post('/lessons/{slug}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

Route::get('/quiz/{code}', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{code}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::get('/quiz/{code}/result', [QuizController::class, 'result'])->name('quiz.result');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/tracks/{slug}/enroll', [TrackController::class, 'enroll'])->name('tracks.enroll');

    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/toggle', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
    Route::get('/review/{id}', [ReviewController::class, 'show'])->name('review.show');
    Route::post('/review/{id}/answer', [ReviewController::class, 'answer'])->name('review.answer');
});

Route::get('/exam', [ExamController::class, 'index'])->name('exam.index');
Route::post('/exam/start', [ExamController::class, 'start'])->name('exam.start');
Route::get('/exam/play', [ExamController::class, 'play'])->name('exam.play');
Route::post('/exam/submit', [ExamController::class, 'submit'])->name('exam.submit');

Route::get('/dictionary', [DictionaryController::class, 'index'])->name('dictionary.index');

Route::get('/lab/sample-chart', [LabController::class, 'sampleChart'])->name('lab.index');
Route::get('/lab/sample-chart/{slug}', [LabController::class, 'showChart'])->name('lab.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('hanja-chars', Admin\HanjaCharController::class);
    Route::resource('learning-tracks', Admin\LearningTrackController::class);
    Route::resource('lessons', Admin\LessonController::class);
    Route::resource('quiz-sets', Admin\QuizSetController::class);
    Route::resource('saju-examples', Admin\SajuExampleController::class);
    Route::get('audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
});

require __DIR__.'/auth.php';

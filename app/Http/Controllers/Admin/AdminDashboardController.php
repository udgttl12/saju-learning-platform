<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HanjaChar;
use App\Models\LearningTrack;
use App\Models\Lesson;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'tracks' => LearningTrack::count(),
            'lessons' => Lesson::count(),
            'hanjas' => HanjaChar::count(),
            'users' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

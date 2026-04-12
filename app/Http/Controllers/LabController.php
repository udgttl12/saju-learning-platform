<?php

namespace App\Http\Controllers;

use App\Models\SajuExample;

class LabController extends Controller
{
    public function sampleChart()
    {
        $examples = SajuExample::where('publish_status', 'published')
            ->orderBy('difficulty_level')
            ->get();

        return view('lab.index', compact('examples'));
    }

    public function showChart(string $slug)
    {
        $example = SajuExample::where('slug', $slug)
            ->where('publish_status', 'published')
            ->firstOrFail();

        $pillars = [
            'year' => ['stem' => $example->year_stem, 'branch' => $example->year_branch, 'label' => '연주'],
            'month' => ['stem' => $example->month_stem, 'branch' => $example->month_branch, 'label' => '월주'],
            'day' => ['stem' => $example->day_stem, 'branch' => $example->day_branch, 'label' => '일주'],
            'hour' => ['stem' => $example->hour_stem, 'branch' => $example->hour_branch, 'label' => '시주'],
        ];

        return view('lab.show', compact('example', 'pillars'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Performance;
use App\Models\WeeklyMetric;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index($branch_code, Request $request)
    {
        $weeklyMetrics = WeeklyMetric::where('branch_code',$branch_code)->orderBy('week_start', 'desc')->get();

        $performances = Performance::whereHas('weeklyMetric', function ($query) use ($branch_code) {
            $query->where('branch_code', $branch_code);
        })->get();

        return view('restaurants.performance', compact('weeklyMetrics','branch_code','performances'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'weekly_metric_id' => 'required|exists:weekly_metrics,id',
            'speed_service' => 'required|string',
            'complaints' => 'nullable|string',
            'osat' => 'required|string',
            'redbook' => 'required|string',
        ]);

        $weekly = WeeklyMetric::find($request->weekly_metric_id);

        $data['sale'] = $weekly->current_year_sale;
        $data['growth'] = $weekly->growth_percentage;

        Performance::create($data);

        return response()->json(['success' => 'Performance added successfully!']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'weekly_metric_id' => 'required|exists:weekly_metrics,id',
            'speed_service' => 'required|string',
            'complaints' => 'nullable|string',
            'osat' => 'required|string',
            'redbook' => 'required|string',
        ]);

        $weekly = WeeklyMetric::find($request->weekly_metric_id);

        $data['sale'] = $weekly->current_year_sale;
        $data['growth'] = $weekly->growth_percentage;

        $performance = Performance::findOrFail($id);
        $performance->update($data);

        return response()->json(['success' => 'Performance updated successfully!']);
    }
}

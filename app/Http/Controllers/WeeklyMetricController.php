<?php

namespace App\Http\Controllers;

use App\Models\WeeklyMetric;
use Illuminate\Http\Request;

class WeeklyMetricController extends Controller
{
    public function index(Request $request)
    {
        $query = WeeklyMetric::orderBy('week_start', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('week_start', [$request->start_date, $request->end_date]);
        }

        $metrics = $query->get();
        return view('restaurants.metrics', compact('metrics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'week_start' => 'required|date',
            'week_end' => 'required|date',
            'ndcp' => 'required|numeric',
            'cml' => 'required|numeric',
            'payrolls' => 'required|numeric',
            'last_year_sale' => 'required|numeric',
            'current_year_sale' => 'required|numeric',
        ]);

        $data['growth'] = $data['current_year_sale'] - $data['last_year_sale'];

        WeeklyMetric::create($data);

        return response()->json(['success' => true]);
    }

    public function downloadCSV()
    {
        $metrics = WeeklyMetric::all();
        $csvData = "Week Start,Week End,NDCP,CML,Payrolls,Last Year Sale,Current Year Sale,Growth%\n";

        foreach ($metrics as $metric) {
            $csvData .= "{$metric->week_start},{$metric->week_end},{$metric->ndcp},{$metric->cml},{$metric->payrolls},{$metric->last_year_sale},{$metric->current_year_sale},{$metric->growth}\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="weekly_metrics.csv"');
    }
}

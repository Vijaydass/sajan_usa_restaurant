<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\WeeklyMetric;
use Illuminate\Http\Request;

class WeeklyMetricController extends Controller
{
    public function index($branch_code, Request $request)
    {
        $query = WeeklyMetric::where('branch_code',$branch_code)->orderBy('week_start', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('week_start', [$request->start_date, $request->end_date]);
        }

        $metrics = $query->get();

        $total_ndcp = $metrics->sum('ndcp');
        $total_cml = $metrics->sum('cml');
        $total_payrolls = $metrics->sum('payrolls');
        $total_payroll_tax = $metrics->sum('payroll_tax');
        $total_last_year_sale = $metrics->sum('last_year_sale');
        $total_current_year_sale = $metrics->sum('current_year_sale');
        $total_growth = $metrics->sum('growth');
        $average_ndcp = round($metrics->avg('ndcp_percentage'),2);
        $average_cml = round($metrics->avg('cml_percentage'),2);
        $average_payrolls = round($metrics->avg('payroll_percentage'),2);
        $average_last_year_sale = $metrics->avg('last_year_sale');
        $average_current_year_sale = $metrics->avg('current_year_sale');
        $average_growth = round($metrics->avg('growth_percentage'),2);
        return view('restaurants.metrics', compact('branch_code','metrics','total_ndcp','total_cml','total_payrolls','total_payroll_tax','total_last_year_sale','total_current_year_sale','total_growth','average_ndcp','average_cml','average_payrolls','average_last_year_sale','average_current_year_sale', 'average_growth'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_code' => 'required|string|exists:restaurants,branch_code',
            'week_start' => 'required|date',
            'week_end' => 'required|date',
            'ndcp' => 'required|numeric',
            'cml' => 'required|numeric',
            'payrolls' => 'required|numeric',
            'payroll_tax' => 'required|numeric',
            'last_year_sale' => 'required|numeric',
            'current_year_sale' => 'required|numeric',
        ]);

        $data['growth'] = $data['current_year_sale'] - $data['last_year_sale'];

        WeeklyMetric::create($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $metric = WeeklyMetric::findOrFail($id);
        return response()->json($metric);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'week_start' => 'required|date',
            'week_end' => 'required|date',
            'ndcp' => 'required|numeric',
            'cml' => 'required|numeric',
            'payrolls' => 'required|numeric',
            'payroll_tax' => 'required|numeric',
            'last_year_sale' => 'required|numeric',
            'current_year_sale' => 'required|numeric',
        ]);

        $data['growth'] = $data['current_year_sale'] - $data['last_year_sale'];

        $metric = WeeklyMetric::findOrFail($id);
        $metric->update($data);

        return response()->json(['success' => true]);
    }


    public function downloadCSV($branch_code, Request $request)
    {
        $query = WeeklyMetric::where('branch_code',$branch_code)->orderBy('week_start', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('week_start', [$request->start_date, $request->end_date]);
        }

        $metrics = $query->get();

        $total_ndcp = $metrics->sum('ndcp');
        $total_cml = $metrics->sum('cml');
        $total_payrolls = $metrics->sum('payrolls');
        $total_payroll_tax = $metrics->sum('payroll_tax');
        $total_last_year_sale = $metrics->sum('last_year_sale');
        $total_current_year_sale = $metrics->sum('current_year_sale');
        $total_growth = $metrics->sum('growth');
        $average_ndcp = round($metrics->avg('ndcp_percentage'),2);
        $average_cml = round($metrics->avg('cml_percentage'),2);
        $average_payrolls = round($metrics->avg('payroll_percentage'),2);
        $average_growth = round($metrics->avg('growth_percentage'),2);

        $csvData = "Week Start,Week End,NDCP,CML,Payrolls,Payroll Tax,Last Year Sale,Current Year Sale,Growth,NDCP %,CML %,Payroll %,Growth %,Big 2\n";

        foreach ($metrics as $metric) {
            $big2 = $metric->payroll_percentage+$metric->ndcp_percentage + $metric->cml_percentage;
            $csvData .= "{$metric->week_start},{$metric->week_end},{$metric->ndcp},{$metric->cml},{$metric->payrolls},{$metric->payroll_tax},{$metric->last_year_sale},{$metric->current_year_sale},{$metric->growth},{$metric->ndcp_percentage},{$metric->cml_percentage},{$metric->payroll_percentage},{$metric->growth_percentage},{$big2}\n";
        }

        $csvData .= "Summary,,$ {$total_ndcp},$ {$total_cml},$ {$total_payrolls},{$total_payroll_tax},{$total_last_year_sale},{$total_current_year_sale},{$total_growth},{$average_ndcp} %,{$average_cml} %,{$average_payrolls}%,{$average_growth}%\n";

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="weekly_metrics.csv"');
    }
}

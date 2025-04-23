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

    public function restaurantPerformances(Request $request)
    {
        $start_date = $request->start_date ?? now()->subMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->format('Y-m-d');

        // Aggregate Data by Branch Code
        $weeklyMetrics = WeeklyMetric::selectRaw('
                branch_code,
                SUM(current_year_sale) as total_current_year_sale,
                SUM(last_year_sale) as total_last_year_sale,
                SUM(ndcp) as total_ndcp,
                SUM(cml) as total_cml,
                SUM(payrolls) as total_payrolls,
                SUM(payroll_tax) as total_payroll_tax
            ')
            ->whereBetween('week_start', [$start_date, $end_date])
            ->groupBy('branch_code')
            ->orderBy('branch_code')
            ->get();

        // Add calculated percentages
        foreach ($weeklyMetrics as $metric) {

    // Total Growth Percentage
    if ($metric->total_last_year_sale != 0) {
            $metric->total_growth_percentage = round(
                (($metric->total_current_year_sale - $metric->total_last_year_sale) / $metric->total_last_year_sale) * 100,
                2
            );
        } else {
            $metric->total_growth_percentage = 0;
        }
    
        // NDCP %
        if ($metric->total_current_year_sale != 0) {
            $metric->total_ndcp_percentage = round(($metric->total_ndcp / $metric->total_current_year_sale) * 100, 2);
        } else {
            $metric->total_ndcp_percentage = 0;
        }
    
        // CML %
        if ($metric->total_current_year_sale != 0) {
            $metric->total_cml_percentage = round(($metric->total_cml / $metric->total_current_year_sale) * 100, 2);
        } else {
            $metric->total_cml_percentage = 0;
        }
    
        // Payroll %
        if ($metric->total_current_year_sale != 0) {
            $metric->total_payroll_percentage = round(
                (($metric->total_payrolls + $metric->total_payroll_tax) / $metric->total_current_year_sale) * 100,
                2
            );
        } else {
            $metric->total_payroll_percentage = 0;
        }
    }


        // Calculate overall averages & totals
        $average_ndcp = round($weeklyMetrics->avg('total_ndcp_percentage'), 2);
        $average_payrolls = round($weeklyMetrics->avg('total_payroll_percentage'), 2);
        $average_cml = round($weeklyMetrics->avg('total_cml_percentage'), 2);
        $total_sales = $weeklyMetrics->sum('total_current_year_sale'); // FIXED
        $average_growth = round($weeklyMetrics->avg('total_growth_percentage'), 2);

        if ($request->export == 'csv') {
            return $this->exportCsv($weeklyMetrics,$average_ndcp,$average_payrolls,$average_cml,$total_sales,$average_growth);
        }

        return view('restaurants.performance-dashboard', compact(
            'weeklyMetrics',
            'average_ndcp',
            'average_payrolls',
            'average_cml',
            'total_sales',
            'average_growth'
        ));
    }

    private function exportCsv($weeklyMetrics,$average_ndcp,$average_payrolls,$average_cml,$total_sales,$average_growth)
    {
        $csvData = "Branch Code,Current Year Sale,Last Year Sale,Growth (%),CML (%), Payroll (%), NDCP (%)\n";

        foreach ($weeklyMetrics as $metric) {
            $csvData .= "{$metric->branch_code},{$metric->total_current_year_sale},{$metric->total_last_year_sale},{$metric->total_growth_percentage},{$metric->total_cml_percentage},{$metric->total_payroll_percentage},{$metric->total_ndcp_percentage}\n";
        }

        $csvData .= "\n"; // Empty row for separation
        $csvData .= "Total Sales,Avg Growth %,Avg CML %,Avg Payrolls %,Avg NDCP %\n" ;
        $csvData .= $total_sales.','. $average_growth . "%,". $average_cml . "%,". $average_payrolls . "%,". $average_ndcp . "%\n";

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="weekly_metrics.csv"');
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

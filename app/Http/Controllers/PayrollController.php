<?php
namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\PayrollExport;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'wk1_hrs' => 'required|numeric|min:0',
            'wk2_hrs' => 'required|numeric|min:0',
            'ot_wk1_hrs' => 'required|numeric|min:0',
            'ot_wk2_hrs' => 'required|numeric|min:0'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        if ($endDate->diffInDays($startDate) !== 6) {
            return response()->json([
                'status' => 'error',
                'message' => 'Start date and end date must cover a full Sunday week.'
            ], 422);
        }

        $existingPayroll = Payroll::where('employee_id', $request->employee_id)
            ->where('start_date', $startDate->format('Y-m-d'))
            ->where('end_date', $endDate->format('Y-m-d'))
            ->exists();

        if ($existingPayroll) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payroll entry already exists for this employee in the same week.'
            ], 422);
        }

        // Fetch Employee's Pay Rate
        $employee = Employee::find($request->employee_id);
        $payRate = $employee->pay_rate;
        $otRate = $payRate * 1.5; // Overtime rate = 1.5 times the pay rate

        // Calculate total pay
        $totalPay = ($request->wk1_hrs + $request->wk2_hrs) * $payRate
                + ($request->ot_wk1_hrs + $request->ot_wk2_hrs) * $otRate;

        // Create Payroll Entry
        Payroll::create([
            'employee_id' => $request->employee_id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'wk1_hrs' => $request->wk1_hrs,
            'wk2_hrs' => $request->wk2_hrs,
            'ot_wk1_hrs' => $request->ot_wk1_hrs,
            'ot_wk2_hrs' => $request->ot_wk2_hrs,
            'total_hrs' => $request->wk1_hrs + $request->wk2_hrs,
            'total_ot_hrs' => $request->ot_wk1_hrs + $request->ot_wk2_hrs,
            'pay_rate' => $payRate,
            'ot_rate' => $otRate,
            'total_pay' => $totalPay,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Payroll entry created successfully!'
        ]);
    }

    public function fetchPayroll(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $payrolls = Payroll::whereBetween('start_date', [$request->start_date, $request->end_date])
            ->with('employee') // Include employee data
            ->get();

        $summary = [
            'total_wk1_hrs' => $payrolls->sum('wk1_hrs'),
            'total_wk2_hrs' => $payrolls->sum('wk2_hrs'),
            'total_hours' => $payrolls->sum('total_hrs'),
            'total_ot_wk1' => $payrolls->sum('ot_wk1_hrs'),
            'total_ot_wk2' => $payrolls->sum('ot_wk2_hrs'),
            'total_ot_hours' => $payrolls->sum('total_ot_hrs'),
            'total_pay' => $payrolls->sum('total_pay'),
        ];

        return response()->json([
            'payrolls' => $payrolls,
            'summary' => $summary
        ]);
    }

    public function exportPayroll(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return Excel::download(new PayrollExport($request->start_date, $request->end_date), 'payroll.xlsx');
    }

}

<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PayrollExport implements FromCollection, WithHeadings, WithStyles
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $payrolls = Payroll::whereBetween('start_date', [$this->start_date, $this->end_date])
            ->with('employee')
            ->get();

        $data = $payrolls->map(function ($payroll) {
            return [
                'Name'         => $payroll->employee->name ?? '',
                'Email'        => $payroll->employee->email ?? '',
                'Start Date'   => $payroll->start_date,
                'End Date'     => $payroll->end_date,
                'Week 1 Hrs'   => $payroll->wk1_hrs,
                'Week 2 Hrs'   => $payroll->wk2_hrs,
                'OT Week 1'    => $payroll->ot_wk1_hrs,
                'OT Week 2'    => $payroll->ot_wk2_hrs,
                'Branch'       => $payroll->branch,
                'Total Hrs'    => $payroll->total_hrs,
                'Total OT Hrs' => $payroll->total_ot_hrs,
                'Designation'  => $payroll->employee->designation ?? '',
                'Pay Rate'     => $payroll->pay_rate,
                'OT Rate'      => $payroll->ot_rate,
                'Total Pay'    => $payroll->total_pay,
            ];
        });

        // Calculate Summary
        $summaryHeading = [
            'Week 1 Hrs'   => '',
            'Week 2 Hrs'   => '',
            'OT Week 1'    => '',
            'OT Week 2'    => '',
            'Total Hrs'    => '',
            'Total OT Hrs' => '',
            'Total Pay'    => '',
        ];

        $summaryHeading2 = [
            'Week 1 Hrs'   => 'Week 1 Hrs',
            'Week 2 Hrs'   => 'Week 2 Hrs',
            'OT Week 1'    => 'OT Week 1',
            'OT Week 2'    => 'OT Week 2',
            'Total Hrs'    => 'Total Hrs',
            'Total OT Hrs' => 'Total OT Hrs',
            'Total Pay'    => 'Total Pay',
        ];

        $summary = [
            'Week 1 Hrs'   => $payrolls->sum('wk1_hrs'),
            'Week 2 Hrs'   => $payrolls->sum('wk2_hrs'),
            'OT Week 1'    => $payrolls->sum('ot_wk1_hrs'),
            'OT Week 2'    => $payrolls->sum('ot_wk2_hrs'),
            'Total Hrs'    => $payrolls->sum('total_hrs'),
            'Total OT Hrs' => $payrolls->sum('total_ot_hrs'),
            'Total Pay'    => $payrolls->sum('total_pay'),
        ];

        $data->push($summaryHeading);
        $data->push($summaryHeading2);

        return $data->push($summary);
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Start Date', 'End Date', 'Week 1 Hrs', 'Week 2 Hrs',
            'OT Week 1', 'OT Week 2', 'Branch', 'Total Hrs', 'Total OT Hrs',
            'Designation', 'Pay Rate', 'OT Rate', 'Total Pay'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow(); // Get the last row index
        $summaryHeading2Row = $highestRow - 1; // Second last row for summary heading

        return [
            1 => ['font' => ['bold' => true]], // Bold header
            $summaryHeading2Row => ['font' => ['bold' => true]], // Bold summary heading
            $highestRow => ['font' => ['bold' => true]] // Bold summary values
        ];
    }
}

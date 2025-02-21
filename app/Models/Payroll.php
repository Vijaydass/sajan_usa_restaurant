<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Payroll extends Model
{
    use HasFactory, Sortable;

    protected $sortable = [
        'name', 'wk1_hrs', 'wk2_hrs','ot_wk1_hrs', 'ot_wk2_hrs', 'total_hrs', 'total_ot_hrs',
        'pay_rate', 'ot_rate', 'total_pay'
    ];

    protected $fillable = [
        'name', 'email', 'start_date', 'end_date', 'wk1_hrs', 'wk2_hrs',
        'ot_wk1_hrs', 'ot_wk2_hrs', 'branch', 'total_hrs', 'total_ot_hrs',
        'designation', 'pay_rate', 'ot_rate', 'total_pay', 'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

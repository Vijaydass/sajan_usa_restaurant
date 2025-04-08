<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start', 'week_end', 'ndcp', 'cml', 'payrolls', 'branch_code',
        'last_year_sale', 'current_year_sale', 'growth','payroll_tax'
    ];

    protected $appends = ['ndcp_percentage', 'cml_percentage', 'payroll_percentage', 'growth_percentage'];

    public function getNdcpPercentageAttribute()
    {
        return $this->current_year_sale ? round(($this->ndcp / $this->current_year_sale) * 100, 2) : 0;
    }

    public function getCmlPercentageAttribute()
    {
        return $this->current_year_sale ? round(($this->cml / $this->current_year_sale) * 100, 2) : 0;
    }

    public function getPayrollPercentageAttribute()
    {
        return $this->current_year_sale ? round((($this->payrolls + $this->payroll_tax) / $this->current_year_sale) * 100, 2) : 0;
    }

    public function getGrowthPercentageAttribute()
    {
        return ($this->last_year_sale != 0)
            ? round((($this->current_year_sale - $this->last_year_sale) / $this->last_year_sale) * 100, 2)
            : 0;
    }

}

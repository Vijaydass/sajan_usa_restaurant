<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Employee extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'branch_code','name', 'email', 'designation', 'address', 'ssn', 'pay_rate',
        'dob', 'routing_number', 'account_number', 'bank', 'mobile', 'start_date'
    ];

    public $sortable = ['name', 'email', 'designation', 'address', 'ssn', 'pay_rate',
        'dob', 'routing_number', 'account_number', 'bank', 'mobile', 'start_date'];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}

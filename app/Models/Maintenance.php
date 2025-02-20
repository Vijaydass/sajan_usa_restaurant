<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Maintenance extends Model
{
    use HasFactory, Sortable;

    protected $fillable = ['branch_code', 'equipment_name', 'payment_type', 'reason', 'status'];

    protected $sortable = ['branch_code', 'equipment_name', 'payment_type', 'reason', 'status'];

    protected $casts = [
        'date' => 'datetime',
    ];
}

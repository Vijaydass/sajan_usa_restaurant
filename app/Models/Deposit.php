<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Deposit extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'branch_code',
        'expected_deposit',
        'actual_deposit',
        'shortage',
        'comments',
        'date',
    ];

    protected $sortable = [
        'branch_code',
        'expected_deposit',
        'actual_deposit',
        'shortage',
        'comments',
        'date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Contact extends Model
{
    use HasFactory, Sortable;

    protected $fillable = ['vendor', 'vendor_hour', 'phone', 'email'];
    protected $sortable = ['vendor', 'vendor_hour', 'phone', 'email'];

}

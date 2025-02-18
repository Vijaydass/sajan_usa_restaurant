<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Restaurant extends Model
{
    use HasFactory, Sortable;

    protected $sortable = ['name', 'address', 'branch_code'];

    protected $fillable = ['name', 'address', 'branch_code'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'restaurant_user');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Other extends Model
{
    protected $fillable = [
        'name',
        'url',
        'order_by',
    ];
}

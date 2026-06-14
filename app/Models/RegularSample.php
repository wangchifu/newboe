<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularSample extends Model
{
    protected $fillable = [        
        'name',        
        'content',
        'section_id',
    ];
}

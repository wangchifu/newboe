<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Introduction extends Model
{
    protected $fillable = [
        'section_id',
        'organization',
        'people',
        'site',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionPage extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'content',
        'order_by',
    ];
}

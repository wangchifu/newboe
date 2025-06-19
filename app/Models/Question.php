<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title',
        'type',
        'options',
        'report_id',
        'show',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}

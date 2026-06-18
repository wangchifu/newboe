<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularReportTemp extends Model
{
    protected $fillable = [
        'regular_report_id',
        'content',
        'code',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
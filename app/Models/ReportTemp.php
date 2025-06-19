<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTemp extends Model
{
    protected $fillable = [
        'report_id',
        'content',
        'code',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
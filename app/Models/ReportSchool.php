<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSchool extends Model
{
    protected $fillable = [
        'report_id',
        'code',
        'signed_user_id',
        'review_user_id',
        'signed_at',
        'situation',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function review_user()
    {
        return $this->belongsTo(User::class,'review_user_id','id');
    }
    public function signed_user()
    {
        return $this->belongsTo(User::class,'signed_user_id','id');
    }
    public function school()
    {
        return $this->belongsTo(School::class,'code','code_no');
    }
}
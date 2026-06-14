<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularReportSchool extends Model
{
    protected $fillable = [
        'regular_report_id',
        'code',
        'signed_user_id',
        'review_user_id',
        'signed_at',
        'situation',
    ];

    public function regular_report()
    {
        return $this->belongsTo(RegularReport::class);
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

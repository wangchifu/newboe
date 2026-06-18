<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularAnswer extends Model
{
    protected $fillable = [
        'answer',
        'regular_report_id',
        'regular_question_id',
        'regular_report_school_id',
        'school_code',
    ];

    public function regular_report_school()
    {
        return $this->belongsTo(RegularReportSchool::class);
    }

    public function regular_question(){
        return $this->belongsTo(RegularQuestion::class);
    }
}

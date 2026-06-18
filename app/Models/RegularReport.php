<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularReport extends Model
{
    protected $fillable = [
        'regular_sample_id',
        'user_id',
        'section_id',
        'pass_user_id',
        'semester',
        'start_date',
        'die_date',        
        'situation',
        'school_set_0',
        'school_set_1',
        'school_set_2',
        'school_set_3',
        'school_set_4',
        'passed_at',
    ];

    public function regular_sample()
    {
        return $this->belongsTo(RegularSample::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pass_user()
    {
        return $this->belongsTo(User::class,'pass_user_id','id');
    }

    public function regular_report_schools()
    {
        return $this->hasMany(RegularReportSchool::class);
    }
}

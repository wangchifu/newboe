<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'pass_user_id',
        'name',
        'die_date',
        'content',
        'section_id',
        'situation',
        'school_set_0',
        'school_set_1',
        'school_set_2',
        'school_set_3',
        'school_set_4',
        'passed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pass_user()
    {
        return $this->belongsTo(User::class,'pass_user_id','id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->where('show','1');
    }

    public function report_schools()
    {
        return $this->hasMany(ReportSchool::class);
    }
}
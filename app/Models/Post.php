<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'post_no',
        'user_id',
        'pass_user_id',
        'title',
        'content',
        'feedback_reason',
        'for_schools',
        'situation',
        'type',
        'another',
        'category_id',
        'section_id',
        'views',
        'url',
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

    public function postschools() {
        return $this->hasMany(PostSchool::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }
}

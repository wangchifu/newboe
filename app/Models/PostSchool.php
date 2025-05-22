<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostSchool extends Model
{
    protected $fillable = [
        'post_id',
        'code',
        'signed_user_id',
        'signed_at',
        'signed_quickly',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'signed_user_id','id');
    }

    public function school()
    {
        return $this->belongsTo(School::class,'code','code_no');
    }
}

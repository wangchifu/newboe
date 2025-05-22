<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'section_id',
        'disable',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

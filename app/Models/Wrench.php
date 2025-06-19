<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wrench extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'reply',
        'show',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

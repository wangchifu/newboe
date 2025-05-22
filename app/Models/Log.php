<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'level',
        'event',
        'user_id',
        'ip',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

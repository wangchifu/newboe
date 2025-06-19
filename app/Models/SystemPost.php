<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemPost extends Model
{
    protected $fillable = [
        'content',
        'start_date',
        'end_date',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

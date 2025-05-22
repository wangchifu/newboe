<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginError extends Model
{
    protected $fillable = [
        'username',
        'error_count',
        'ip',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

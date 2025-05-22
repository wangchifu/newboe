<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPower extends Model
{
    protected $fillable = [
        'section_id',
        'user_id',
        'power_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

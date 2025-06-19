<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRead extends Model
{
    protected $fillable = [
        'user_id',
        'system_post_id',
    ];
}

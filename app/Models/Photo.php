<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'photo_name',
        'describe',
        'photo_album_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

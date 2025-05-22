<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model
{
    protected $fillable = [
        'album_name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitleImage extends Model
{
    protected $fillable = [
        'photo_name',
        'title',
        'content',
        'link',
        'user_id',
        'disable',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

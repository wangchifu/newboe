<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'name',
        'type',
        'url',
        'folder_id',
        'user_id',
        'order_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

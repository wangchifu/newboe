<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolIntroduction extends Model
{
    protected $fillable = [    
        'code_no',
        'pic1',
        'pic2',
        'introduction1',
        'introduction2',
        'website',
        'facebook',
        'wiki',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

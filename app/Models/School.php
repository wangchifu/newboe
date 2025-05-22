<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';


    protected $fillable = [
        'school_name',
        'code_no',
        'township_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularQuestion extends Model
{
    protected $fillable = [
        'title',
        'cht_title',
        'type',
        'options',
        'regular_sample_id',
        'show',
    ];

    public function regular_sample()
    {
        return $this->belongsTo(RegularSample::class);
    }
}

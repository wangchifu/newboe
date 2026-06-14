<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularQuestion extends Model
{
    protected $fillable = [
        'title',
        'type',
        'options',
        'regular_report_id',
        'show',
    ];

    public function regular_report()
    {
        return $this->belongsTo(RegularReport::class);
    }
}

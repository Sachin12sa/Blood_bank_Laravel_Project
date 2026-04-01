<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpiryAlert extends Model
{
    protected $fillable = [
        'blood_unit_id', 'notified', 'alerted_at',
    ];

    protected $casts = [
        'notified'   => 'boolean',
        'alerted_at' => 'datetime',
    ];

    public function bloodUnit()
    {
        return $this->belongsTo(BloodUnit::class);
    }
}
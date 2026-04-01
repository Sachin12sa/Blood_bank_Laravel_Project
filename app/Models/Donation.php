<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_id', 'blood_unit_id',
        'donated_at', 'certificate_path',
    ];

    protected $casts = [
        'donated_at' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function bloodUnit()
    {
        return $this->belongsTo(BloodUnit::class);
    }
}
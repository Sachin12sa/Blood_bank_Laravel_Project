<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_id', 'blood_unit_id',
        'donated_at', 'certificate_path', 'status',
    ];

    protected $casts = [
        'donated_at' => 'date',
        'status' => 'string',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function bloodUnit()
    {
        return $this->belongsTo(BloodUnit::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDonated($query)
    {
        return $query->where('status', 'donated');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}


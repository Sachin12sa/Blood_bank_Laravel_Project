<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodUnit extends Model
{
    protected $fillable = [
        'blood_group', 'donor_id',
        'collected_at', 'expires_at', 'status',
    ];

    protected $casts = [
        'collected_at' => 'date',
        'expires_at'   => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function donation()
    {
        return $this->hasOne(Donation::class);
    }

    public function expiryAlert()
    {
        return $this->hasOne(ExpiryAlert::class);
    }

    // ── Query Scopes ──────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByBloodGroup($query, string $group)
    {
        return $query->where('blood_group', $group);
    }

    public function scopeExpiringSoon($query, int $days = 3)
    {
        return $query->where('status', 'available')
                     ->whereBetween('expires_at', [
                         now()->toDateString(),
                         now()->addDays($days)->toDateString(),
                     ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
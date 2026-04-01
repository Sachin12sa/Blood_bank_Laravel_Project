<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $fillable = [
        'user_id', 'blood_group', 'phone',
        'address', 'date_of_birth',
        'is_eligible', 'last_donated_at',
    ];

    protected $casts = [
        'is_eligible'     => 'boolean',
        'last_donated_at' => 'datetime',
        'date_of_birth'   => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function bloodUnits()
    {
        return $this->hasMany(BloodUnit::class);
    }

    // ── Query Scopes ──────────────────────────────
    public function scopeEligible($query)
    {
        return $query->where('is_eligible', true)
                     ->where(function ($q) {
                         $q->whereNull('last_donated_at')
                           ->orWhere('last_donated_at', '<=',
                               now()->subDays(56));
                     });
    }

    public function scopeByBloodGroup($query, string $group)
    {
        return $query->where('blood_group', $group);
    }

    // Helper: check if donor is in 56-day cooldown
    public function isInCooldown(): bool
    {
        if (!$this->last_donated_at) return false;
        return $this->last_donated_at->diffInDays(now()) < 56;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $fillable = [
        'hospital_id', 'urgency', 'status',
        'notes', 'approved_at', 'dispatched_at', 'received_at',
    ];

    protected $casts = [
        'approved_at'   => 'datetime',
        'dispatched_at' => 'datetime',
        'received_at'   => 'datetime',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCritical($query)
    {
        return $query->where('urgency', 'critical');
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', 'dispatched');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }
}

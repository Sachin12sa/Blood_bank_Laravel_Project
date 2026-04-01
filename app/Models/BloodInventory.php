<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    protected $fillable = [
        'blood_group', 'total_units',
        'available_units', 'threshold',
    ];

    public function isBelowThreshold(): bool
    {
        return $this->available_units < $this->threshold;
    }
}
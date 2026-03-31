<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    protected $fillable = [
        'blood_group',
        'total_units',
        'available_units',
        'threshold',
    ];

    protected $table = 'blood_inventories';
}

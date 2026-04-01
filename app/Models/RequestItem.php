<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'blood_request_id', 'blood_group',
        'units_requested', 'units_fulfilled',
    ];

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }
}
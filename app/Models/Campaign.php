<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['title', 'description', 'address', 'date', 'status'];
    
    protected $casts = [
        'date' => 'date'
    ];
}

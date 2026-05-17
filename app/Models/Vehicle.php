<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'make',
        'model',
        'year',
        'plate_number',
        'capacity',
        'child_seat_count',
        'insurance_status',
        'registration_status',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }
}

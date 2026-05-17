<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_request_id',
        'driver_id',
        'vehicle_id',
        'school_id',
        'status',
        'pickup_time',
        'dropoff_time',
        'eta',
        'distance_km',
        'fare_estimate',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'dropoff_time' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function rideRequest()
    {
        return $this->belongsTo(RideRequest::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function locations()
    {
        return $this->hasMany(RideLocation::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

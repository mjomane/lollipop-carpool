<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'driver_lat',
        'driver_lng',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}

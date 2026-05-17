<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'provider',
        'provider_payment_id',
        'provider_data',
        'paid_at',
    ];

    protected $casts = [
        'provider_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
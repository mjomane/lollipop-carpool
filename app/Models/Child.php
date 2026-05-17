<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'school_id',
        'name',
        'grade',
        'dob',
        'photo_url',
        'special_needs',
        'pickup_notes',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function authorizedContacts()
    {
        return $this->hasMany(AuthorizedContact::class);
    }

    public function rideRequests()
    {
        return $this->hasMany(RideRequest::class);
    }
}

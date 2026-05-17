<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'name',
        'relationship',
        'phone',
        'is_primary',
        'allowed_for_pickup',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'allowed_for_pickup' => 'boolean',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'approved_by',
        'paid_contribution',
    ];

    /**
     * Het lidmaatschap hoort bij een specifieke gebruiker (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Een lid kan als chauffeur meerdere ritten aanmaken.
     */
    public function rides()
    {
        return $this->hasMany(Ride::class, 'driver_id');
    }
}

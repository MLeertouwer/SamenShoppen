<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'driver_id',
        'destination_store',
        'departure_time',
        'max_passengers',
        'status',
        'departure_longitude',
        'departure_latitude',
    ];

    /**
     * De relatie: een rit hoort bij een chauffeur (Membership).
     */
    public function driver()
    {
        return $this->belongsTo(Membership::class, 'driver_id');
    }

    /**
     * Samenvoegen van de coördinaten voor departure_place.
     */
    public function getDepartureplace(): array
    {
        return [
            'longitude' => $this->departure_longitude,
            'latitude'  => $this->departure_latitude,
        ];
    }
}

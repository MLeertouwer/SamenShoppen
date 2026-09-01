<?php

namespace App\Models;

use App\Enums\PassengerStatus;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'driver_id',
        'destination_store',
        'destination_longitude',
        'destination_latitude',
        'destination_address',
        'departure_time',
        'max_passengers',
        'status',
        'departure_longitude',
        'departure_latitude',
        'departure_address',
    ];

    /**
     * De relatie: een rit hoort bij een chauffeur (Membership).
     */
    public function driver()
    {
        return $this->belongsTo(Membership::class, 'driver_id');
    }

    /**
     * De relatie: een rit kan meerdere passagiers hebben, en een passagier kan meerijden met meerdere ritten.
     */
    public function passengers()
    {
        return $this->belongsToMany(Membership::class, 'ride_passenger')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Helper functions.
     */
    public function approvedPassengers()
    {
        return $this->passengers()->wherePivot('status', PassengerStatus::APPROVED->value);
    }

    public function pendingPassengers()
    {
        return $this->passengers()->wherePivot('status', PassengerStatus::PENDING->value);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


use Illuminate\Database\Eloquent\Model;

class RidePassenger extends Model
{
    protected $table = 'ride_passenger';

    protected $fillable = [
        'ride_id',
        'membership_id',
        'status',
        'delivery_address',
        'liked',
    ];

    // Relatie naar shoppinglist
    public function shoppingList(): HasOne
    {
        return $this->hasOne(ShoppingList::class, 'ride_passenger_id');
    }

    // Relatie naar ride
    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}

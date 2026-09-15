<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingList extends Model
{
    protected $table = 'shoppinglists';

    protected $fillable = [
        'ride_passenger_id',
        'title',
        'note',
        'is_delivery_request',
    ];

    // Een op veel relatie met ShoppingListItem
    public function items()
    {
        return $this->hasMany(ShoppingListItem::class, 'shoppinglist_id');
    }

    // Relatie met RidePassenger
    public function ridePassenger(): BelongsTo
    {
        return $this->belongsTo(RidePassenger::class, 'ride_passenger_id');
    }
}

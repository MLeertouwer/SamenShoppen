<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListItem extends Model
{
    protected $fillable = [
        'shoppinglist_id',
        'name',
        'quantity',
        'is_completed',
    ];


    // Een op veel relatie met ShoppingList
    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class, 'shoppinglist_id');
    }
}

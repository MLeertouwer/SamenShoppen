<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Message extends Model
{

    protected $fillable = [
        'ride_id',
        'membership_id',
        'message_text',
    ];


    /**
     * De relatie: Een rit kan meerdere messages hebben, maar een message hoort maar bij 1 rit.
     */
    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    /**
     * De relatie: Een membership (user) kan meerdere messages hebben, maar een message hoort maar bij 1 membership (user).
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }
}

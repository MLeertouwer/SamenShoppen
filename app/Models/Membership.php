<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{

    protected $fillable = [
        'user_id',           // Cruciaal: de koppeling naar de Users tabel!
        'status',            // Verplicht (*)
        'approved_by',       // Verplicht (*)
        'paid_contribution', // Optioneel (O), maar handig als dit via een formulier/vinkje komt
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

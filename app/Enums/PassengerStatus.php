<?php

namespace App\Enums;

/**
 * Vaste waarden instellen voor de status kolom in ride_passenger.
 */
enum PassengerStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

<?php

namespace App\Enum;

/**
 * Reservation status enum.
 */
enum ReservationStatus: string
{
    case ACTIVE = 'ACTIVE';
    case RETURNED = 'RETURNED';
}

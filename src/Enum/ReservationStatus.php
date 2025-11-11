<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case ACTIVE = 'ACTIVE';
    case RETURNED = 'RETURNED';
}

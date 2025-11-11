<?php

namespace App\Enum;

enum FuelType: string
{
    case GASOLINE = 'GASOLINE';
    case DIESEL = 'DIESEL';
    case ELECTRIC = 'ELECTRIC';
    case HYBRID = 'HYBRID';
}

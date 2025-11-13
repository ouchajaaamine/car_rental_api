<?php

namespace App\Enum;

enum UserRole: string
{
    case MANAGER = 'ROLE_MANAGER';
    case CUSTOMER = 'ROLE_CUSTOMER';
}

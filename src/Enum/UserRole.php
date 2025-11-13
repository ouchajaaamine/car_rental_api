<?php

namespace App\Enum;

/**
 * User roles enum.
 */
enum UserRole: string
{
    case MANAGER = 'ROLE_MANAGER';
    case CUSTOMER = 'ROLE_CUSTOMER';
}

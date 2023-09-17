<?php

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case MANAGER = 'Manager';

    /**
     * Get list of all cases.
     *
     * @return array
     */
    public static function list(): array
    {
        return self::cases();
    }
}

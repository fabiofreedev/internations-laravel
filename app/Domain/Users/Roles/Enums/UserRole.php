<?php

namespace App\Domain\Users\Roles\Enums;

enum UserRole: string
{
    case ADMIN = 'Admin';

    /**
     * @return array<string>
     */
    public static function getArrayValues(): array
    {
        return \array_column(self::cases(), 'value');
    }
}

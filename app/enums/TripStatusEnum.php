<?php

namespace App\enums;

enum TripStatusEnum: string
{
    case DELIVERED = 'delivered';
    case PICKED = 'picked';
    case AT_VENDOR = 'at_vendor';
    case ASSIGNED = 'assigned';

    public static function getValidStatusListToNewEstimate(): array
    {
        return [
            self::DELIVERED,
            self::PICKED,
            self::ASSIGNED
        ];
    }
}

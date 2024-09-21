<?php

namespace App\Enums\Order;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum OrderStatusEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case PAID         = 'paid';
    case UNPAID       = 'unpaid';
    case PARTIAL_PAID = 'partial_paid';
    case OVER_PAID    = 'over_paid';
    case SETTLED      = 'settled';

    public static function labels(): array
    {
        return [
            self::PAID->value         => "Paid",
            self::UNPAID->value       => "Unpaid",
            self::PARTIAL_PAID->value => "Partial Paid",
            self::OVER_PAID->value    => "Over Paid",
            self::SETTLED->value      => "Settled",
        ];
    }
}

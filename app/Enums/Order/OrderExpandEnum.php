<?php

namespace App\Enums\Order;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum OrderExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case CUSTOMER = 'customer';
    case ORDER_ITEMS = 'orderItems';

    public static function labels(): array
    {
        return [
            self::CUSTOMER->value => "customer",
            self::ORDER_ITEMS->value => "orderItems",
        ];
    }
}

<?php

namespace App\Enums\Order;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum OrderPayByEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case CASH      = 'cash';
    case BANK      = 'bank';
    case GIFT_CARD = 'gift_card';

    public static function labels(): array
    {
        return [
            self::CASH->value      => "Cash",
            self::BANK->value      => "Bank",
            self::GIFT_CARD->value => "Gift Card",
        ];
    }
}

<?php

namespace App\Enums\Cart;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum CartFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID         = 'id';
    case USER_ID    = 'user_id';
    case PRODUCT_ID = 'product_id';
    case QUANTITY   = 'quantity';
    case CREATED_AT = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value         => "Id",
            self::USER_ID->value    => "User",
            self::PRODUCT_ID->value => "Product",
            self::QUANTITY->value   => "Quantity",
        ];
    }
}

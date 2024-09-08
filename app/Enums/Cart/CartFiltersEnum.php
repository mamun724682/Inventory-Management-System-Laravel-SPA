<?php

namespace App\Enums\Cart;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum CartFiltersEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID            = 'id';
    case USER_ID       = 'user_id';
    case PRODUCT_ID    = 'product_id';
    case PRODUCT_NAME  = 'product_name';
    case QUANTITY      = 'quantity';
    case PRODUCT_PRICE = 'product_price';
    case SUB_TOTAL     = 'sub_total';
    case CREATED_AT    = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value            => "Id",
            self::USER_ID->value       => "User",
            self::PRODUCT_ID->value    => "Product",
            self::PRODUCT_NAME->value  => "Product Name",
            self::QUANTITY->value      => "Quantity",
            self::PRODUCT_PRICE->value => "Product Price",
            self::SUB_TOTAL->value     => "Sub Total",
        ];
    }
}

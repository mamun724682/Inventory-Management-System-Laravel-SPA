<?php

namespace App\Enums\OrderItem;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum OrderItemFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID           = 'id';
    case ORDER_ID     = 'order_id';
    case PRODUCT_ID   = 'product_id';
    case PRODUCT_JSON = 'product_json';
    case QUANTITY     = 'quantity';
    case CREATED_AT   = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value           => "Id",
            self::ORDER_ID->value     => "Order ID",
            self::PRODUCT_ID->value   => "Product ID",
            self::PRODUCT_JSON->value => "Product Json",
            self::QUANTITY->value     => "Quantity",
        ];
    }
}

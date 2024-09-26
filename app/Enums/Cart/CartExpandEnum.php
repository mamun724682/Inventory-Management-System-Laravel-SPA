<?php

namespace App\Enums\Cart;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum CartExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case PRODUCT = 'product';
    case PRODUCT_UNIT_TYPE = 'product.unitType';

    public static function labels(): array
    {
        return [
            self::PRODUCT->value => "Product",
            self::PRODUCT_UNIT_TYPE->value => "Product Unit Type",
        ];
    }
}

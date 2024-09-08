<?php

namespace App\Enums\Cart;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum CartExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case PRODUCT = 'product';

    public static function labels(): array
    {
        return [
            self::PRODUCT->value => "Product",
        ];
    }
}

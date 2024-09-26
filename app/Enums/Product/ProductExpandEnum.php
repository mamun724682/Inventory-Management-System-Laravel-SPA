<?php

namespace App\Enums\Product;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum ProductExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case CATEGORY  = 'category';
    case SUPPLIER  = 'supplier';
    case UNIT_TYPE = 'unitType';

    public static function labels(): array
    {
        return [
            self::CATEGORY->value  => "category",
            self::SUPPLIER->value  => "supplier",
            self::UNIT_TYPE->value => "unit_type",
        ];
    }
}

<?php

namespace App\Enums\Product;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum ProductExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case CATEGORY = 'category';
    case SUPPLIER = 'supplier';

    public static function labels(): array
    {
        return [
            self::CATEGORY->value => "category",
            self::SUPPLIER->value => "supplier",
        ];
    }
}

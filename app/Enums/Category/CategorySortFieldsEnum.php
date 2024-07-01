<?php

namespace App\Enums\Category;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum CategorySortFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID         = 'id';
    case NAME       = 'name';
    case CREATED_AT = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value   => "Id",
            self::NAME->value => "Name",
        ];
    }
}

<?php

namespace App\Enums\UnitType;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum UnitTypeFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID         = 'id';
    case NAME       = 'name';
    case SYMBOL     = 'symbol';
    case CREATED_AT = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value     => "Id",
            self::NAME->value   => "Name",
            self::SYMBOL->value => "Symbol",
        ];
    }
}

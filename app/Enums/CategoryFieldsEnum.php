<?php

namespace App\Enums;

enum CategoryFieldsEnum: string implements BaseEnumInterface
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

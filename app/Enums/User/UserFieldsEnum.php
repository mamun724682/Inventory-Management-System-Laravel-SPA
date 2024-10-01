<?php

namespace App\Enums\User;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum UserFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID                = 'id';
    case NAME              = 'name';
    case EMAIL             = 'email';
    case EMAIL_VERIFIED_AT = 'email_verified_at';
    case PASSWORD          = 'password';
    case PHOTO             = 'photo';
    case CREATED_AT        = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value                => "Id",
            self::NAME->value              => "Name",
            self::EMAIL->value             => "Email",
            self::EMAIL_VERIFIED_AT->value => "Email verified at",
            self::PASSWORD->value          => "Password",
            self::PHOTO->value             => "Photo",
        ];
    }
}

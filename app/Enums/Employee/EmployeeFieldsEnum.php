<?php

namespace App\Enums\Employee;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum EmployeeFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID           = 'id';
    case NAME         = 'name';
    case EMAIL        = 'email';
    case PHONE        = 'phone';
    case DESIGNATION  = 'designation';
    case ADDRESS      = 'address';
    case SALARY       = 'salary';
    case PHOTO        = 'photo'; // nullable
    case NID          = 'nid'; // nullable
    case JOINING_DATE = 'joining_date';
    case CREATED_AT   = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value           => "Id",
            self::NAME->value         => "Name",
            self::EMAIL->value        => "Email",
            self::PHONE->value        => "Phone",
            self::DESIGNATION->value  => "Designation",
            self::ADDRESS->value      => "Address",
            self::SALARY->value       => "Salary",
            self::PHOTO->value        => "Photo",
            self::NID->value          => "NID",
            self::JOINING_DATE->value => "Joining Date",
        ];
    }
}

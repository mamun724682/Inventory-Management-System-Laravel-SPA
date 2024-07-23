<?php

namespace App\Enums\Employee;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum EmployeeSortFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID           = 'id';
    case NAME         = 'name';
    case EMAIL        = 'email';
    case DESIGNATION  = 'designation';
    case SALARY       = 'salary';
    case JOINING_DATE = 'joining_date';
    case CREATED_AT   = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value           => "Id",
            self::NAME->value         => "Name",
            self::EMAIL->value        => "Email",
            self::DESIGNATION->value  => "Designation",
            self::SALARY->value       => "Salary",
            self::JOINING_DATE->value => "Joining Date",
        ];
    }
}

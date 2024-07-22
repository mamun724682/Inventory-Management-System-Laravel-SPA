<?php

namespace App\Enums\Salary;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum SalarySortFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID          = 'id';
    case EMPLOYEE_ID = 'employee_id';
    case AMOUNT      = 'amount';
    case SALARY_DATE = 'salary_date';
    case CREATED_AT  = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value          => "Id",
            self::EMPLOYEE_ID->value => "Employee Id",
            self::AMOUNT->value      => "Amount",
            self::SALARY_DATE->value => "Salary Date",
        ];
    }
}

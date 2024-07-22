<?php

namespace App\Enums\Salary;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum SalaryExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case EMPLOYEE = 'employee';

    public static function labels(): array
    {
        return [
            self::EMPLOYEE->value => "employee",
        ];
    }
}

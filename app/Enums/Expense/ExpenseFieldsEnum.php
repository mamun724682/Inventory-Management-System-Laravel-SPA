<?php

namespace App\Enums\Expense;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum ExpenseFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID           = 'id';
    case NAME         = 'name';
    case DESCRIPTION  = 'description'; // nullable
    case AMOUNT       = 'amount';
    case EXPENSE_DATE = 'expense_date';
    case CREATED_AT   = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value           => "Id",
            self::NAME->value         => "Name",
            self::DESCRIPTION->value  => "Description",
            self::AMOUNT->value       => "Amount",
            self::EXPENSE_DATE->value => "Expense Date",
        ];
    }
}

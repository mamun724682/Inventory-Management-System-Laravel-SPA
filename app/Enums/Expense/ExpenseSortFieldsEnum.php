<?php

namespace App\Enums\Expense;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum ExpenseSortFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID           = 'id';
    case NAME         = 'name';
    case AMOUNT       = 'amount';
    case EXPENSE_DATE = 'expense_date';
    case CREATED_AT   = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value           => "Id",
            self::NAME->value         => "Name",
            self::AMOUNT->value       => "Amount",
            self::EXPENSE_DATE->value => "Expense Date",
        ];
    }
}

<?php

namespace App\Enums\Transaction;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum TransactionFiltersEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID                 = 'id';
    case ORDER_ID           = 'order_id';
    case TRANSACTION_NUMBER = 'transaction_number';
    case AMOUNT             = 'amount';
    case PAID_THROUGH       = 'paid_through';
    case CREATED_AT         = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value                 => "Id",
            self::ORDER_ID->value           => "Order",
            self::TRANSACTION_NUMBER->value => "Transaction Number",
            self::AMOUNT->value             => "Amount",
            self::PAID_THROUGH->value       => "Paid Through",
        ];
    }
}

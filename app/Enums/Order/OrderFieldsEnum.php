<?php

namespace App\Enums\Order;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum OrderFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID             = 'id';
    case CUSTOMER_ID    = 'customer_id';
    case ORDER_NUMBER   = 'order_number';
    case SUB_TOTAL      = 'sub_total';
    case TAX_TOTAL      = 'tax_total';
    case DISCOUNT_TOTAL = 'discount_total';
    case TOTAL          = 'total';
    case PAID           = 'paid';
    case DUE            = 'due';
    case PROFIT         = 'profit';
    case LOSS           = 'loss';
    case STATUS         = 'status';
    case CREATED_AT     = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value             => "Id",
            self::CUSTOMER_ID->value    => "Customer ID",
            self::ORDER_NUMBER->value   => "Order Number",
            self::SUB_TOTAL->value      => "Sub Total",
            self::TAX_TOTAL->value      => "Total Tax",
            self::DISCOUNT_TOTAL->value => "Total Discount",
            self::TOTAL->value          => "Total",
            self::PAID->value           => "Paid",
            self::DUE->value            => "Due",
            self::PROFIT->value         => "Profit",
            self::LOSS->value           => "Loss",
            self::STATUS->value         => "Status",
        ];
    }
}

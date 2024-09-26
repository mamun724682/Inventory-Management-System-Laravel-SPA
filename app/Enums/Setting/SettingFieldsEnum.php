<?php

namespace App\Enums\Setting;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum SettingFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case DECIMAL_POINT   = 'decimal_point';
    case DISCOUNT        = 'discount';
    case TAX             = 'tax';
    case CURRENCY_SYMBOL = 'currency_symbol';

    public static function labels(): array
    {
        return [
            self::DECIMAL_POINT->value   => "Decimal Point",
            self::DISCOUNT->value        => "Discount",
            self::TAX->value             => "Tax",
            self::CURRENCY_SYMBOL->value => "Currency Symbol",
        ];
    }
}

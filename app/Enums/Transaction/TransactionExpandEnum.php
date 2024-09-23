<?php

namespace App\Enums\Transaction;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum TransactionExpandEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ORDER = 'order';

    public static function labels(): array
    {
        return [
            self::ORDER->value => "Order",
        ];
    }
}

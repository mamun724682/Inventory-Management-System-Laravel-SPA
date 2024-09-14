<?php

namespace App\Enums\Core;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum AmountTypeEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case FIXED      = 'fixed';
    case PERCENTAGE = 'percentage';

    public static function labels(): array
    {
        return [
            self::FIXED->value      => "Fixed",
            self::PERCENTAGE->value => "Percentage",
        ];
    }
}

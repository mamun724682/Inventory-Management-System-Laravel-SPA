<?php

namespace App\Enums\Core;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum SortOrderEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ASC  = 'asc';
    case DESC = 'desc';

    public static function labels(): array
    {
        return [
            self::ASC->value  => "ASC",
            self::DESC->value => "DESC",
        ];
    }
}

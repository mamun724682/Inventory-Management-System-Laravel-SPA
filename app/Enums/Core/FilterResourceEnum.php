<?php

namespace App\Enums\Core;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum FilterResourceEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case CATEGORIES = 'categories.index';
    case EMPLOYEES  = 'employees.index';
    case SUPPLIERS  = 'suppliers.index';

    public static function labels(): array
    {
        return [];
    }
}

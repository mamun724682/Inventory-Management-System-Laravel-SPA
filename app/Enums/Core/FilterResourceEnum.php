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
    case CUSTOMERS  = 'customers.index';
    case ORDERS     = 'orders.index';
    case UNIT_TYPES = 'unit-types.index';

    public static function labels(): array
    {
        return [];
    }
}

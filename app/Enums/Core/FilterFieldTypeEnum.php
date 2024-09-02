<?php

namespace App\Enums\Core;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum FilterFieldTypeEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case STRING              = 'string';
    case NUMBER              = 'number';
    case NUMBER_RANGE        = 'number_range';
    case SELECT              = 'select';
    case SELECT_STATIC       = 'select_static';
    case MULTI_SELECT        = 'multiselect';
    case MULTI_SELECT_STATIC = 'multiselect_static';
    case DATE                = 'date';
    case MONTH               = 'month';
    case DATE_RANGE          = 'date_range';
    case DATETIME_RANGE      = 'datetime_range';
    case DATETIME            = 'datetime';
    case BOOLEAN             = 'boolean';

    public static function labels(): array
    {
        return [];
    }
}

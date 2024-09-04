<?php

namespace App\Enums\Product;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum ProductFiltersEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID             = 'id';
    case CATEGORY_ID    = 'category_id';
    case SUPPLIER_ID    = 'supplier_id'; // nullable
    case NAME           = 'name';
    case PRODUCT_NUMBER = 'product_number';
    case PRODUCT_CODE   = 'product_code'; // nullable
    case BUYING_PRICE   = 'buying_price';
    case SELLING_PRICE  = 'selling_price';
    case BUYING_DATE    = 'buying_date'; // nullable
    case QUANTITY       = 'quantity';
    case QUANTITIES     = 'quantities';
    case STATUS         = 'status';
    case CREATED_AT     = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value             => "Id",
            self::CATEGORY_ID->value    => "Category",
            self::SUPPLIER_ID->value    => "Supplier",
            self::PRODUCT_NUMBER->value => "Product Number",
            self::NAME->value           => "Name",
            self::PRODUCT_CODE->value   => "Product Code",
            self::BUYING_PRICE->value   => "Buying Price",
            self::SELLING_PRICE->value  => "Selling Price",
            self::BUYING_DATE->value    => "Buying Date",
            self::QUANTITY->value       => "Quantity",
            self::STATUS->value         => "Status",
        ];
    }
}

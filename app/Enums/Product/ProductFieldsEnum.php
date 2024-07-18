<?php

namespace App\Enums\Product;

use App\Enums\BaseEnumInterface;
use App\Enums\BaseEnumTrait;

enum ProductFieldsEnum: string implements BaseEnumInterface
{
    use BaseEnumTrait;

    case ID            = 'id';
    case CATEGORY_ID   = 'category_id';
    case SUPPLIER_ID   = 'supplier_id'; // nullable
    case NAME          = 'name';
    case DESCRIPTION   = 'description'; // nullable
    case PRODUCT_CODE  = 'product_code'; // nullable
    case ROOT          = 'root'; // nullable
    case BUYING_PRICE  = 'buying_price';
    case SELLING_PRICE = 'selling_price';
    case BUYING_DATE   = 'buying_date'; // nullable
    case QUANTITY      = 'quantity';
    case PHOTO         = 'photo';
    case CREATED_AT    = 'created_at';

    public static function labels(): array
    {
        return [
            self::ID->value            => "Id",
            self::CATEGORY_ID->value   => "Category ID",
            self::SUPPLIER_ID->value   => "Supplier ID",
            self::NAME->value          => "Name",
            self::DESCRIPTION->value   => "Description",
            self::PRODUCT_CODE->value  => "Product Code",
            self::ROOT->value          => "Root",
            self::BUYING_PRICE->value  => "Buying Price",
            self::SELLING_PRICE->value => "Selling Price",
            self::BUYING_DATE->value   => "Buying Date",
            self::QUANTITY->value      => "Quantity",
            self::PHOTO->value         => "Photo",
        ];
    }
}

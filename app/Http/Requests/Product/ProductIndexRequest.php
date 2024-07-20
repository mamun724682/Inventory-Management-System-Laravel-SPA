<?php

namespace App\Http\Requests\Product;

use App\Enums\Category\CategoryFieldsEnum;
use App\Enums\Category\CategorySortFieldsEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Product\ProductExpandEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends BaseIndexRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ProductFiltersEnum::CATEGORY_ID->value          => ["nullable", "integer"],
            ProductFiltersEnum::SUPPLIER_ID->value          => ["nullable", "integer"],
            ProductFiltersEnum::NAME->value                 => ["nullable", "max:255"],
            ProductFiltersEnum::PRODUCT_CODE->value         => ["nullable", "max:255"],
            ProductFiltersEnum::BUYING_PRICE->value         => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::BUYING_PRICE->value . ".*"  => ["required", "decimal", "min:0"],
            ProductFiltersEnum::SELLING_PRICE->value        => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::SELLING_PRICE->value . ".*" => ["required", "decimal", "min:0"],
            ProductFiltersEnum::BUYING_DATE->value          => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::BUYING_DATE->value . ".*"   => ["required", "date"],
            ProductFiltersEnum::QUANTITY->value             => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::QUANTITY->value . ".*"      => ["required", "integer"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(CategoryFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(CategorySortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "expand"       => ["nullable", "array"],
            "expand.*"     => ["required", "string", Rule::in(ProductExpandEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }
}

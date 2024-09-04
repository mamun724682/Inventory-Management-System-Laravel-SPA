<?php

namespace App\Http\Requests\Product;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Product\ProductExpandEnum;
use App\Enums\Product\ProductFieldsEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Enums\Product\ProductSortFieldsEnum;
use App\Enums\Product\ProductStatusEnum;
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
            ProductFiltersEnum::PRODUCT_NUMBER->value       => ["nullable", "max:255"],
            ProductFiltersEnum::PRODUCT_CODE->value         => ["nullable", "max:255"],
            ProductFiltersEnum::BUYING_PRICE->value         => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::BUYING_PRICE->value . ".*"  => ["required", "decimal", "min:0"],
            ProductFiltersEnum::SELLING_PRICE->value        => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::SELLING_PRICE->value . ".*" => ["required", "decimal", "min:0"],
            ProductFiltersEnum::BUYING_DATE->value          => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::BUYING_DATE->value . ".*"   => ["required", "date"],
            ProductFiltersEnum::QUANTITIES->value           => ["nullable", "array", "min:2", "max:2"],
            ProductFiltersEnum::QUANTITIES->value . ".*"    => ["required", "integer"],
            ProductFiltersEnum::STATUS->value               => ["nullable", "string", Rule::in(ProductStatusEnum::values())],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(ProductFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(ProductSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "expand"       => ["nullable", "array"],
            "expand.*"     => ["required", "string", Rule::in(ProductExpandEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $values = [];

        // Buying price
        if ($this->has(ProductFiltersEnum::BUYING_PRICE->value) && $this->filled(ProductFiltersEnum::BUYING_PRICE->value)) {
            $buyingPrices = explode("-", $this->get(ProductFiltersEnum::BUYING_PRICE->value));
            if (count($buyingPrices) === 1 || (count($buyingPrices) === 2 && $buyingPrices[1] == "")) {
                $values[ProductFiltersEnum::BUYING_PRICE->value] = [
                    $buyingPrices[0],
                    $buyingPrices[0]
                ];
            } else {
                $values[ProductFiltersEnum::BUYING_PRICE->value] = $buyingPrices;
            }
        }

        // Selling price
        if ($this->has(ProductFiltersEnum::SELLING_PRICE->value) && $this->filled(ProductFiltersEnum::SELLING_PRICE->value)) {
            $sellingPrices = explode("-", $this->get(ProductFiltersEnum::SELLING_PRICE->value));
            if (count($sellingPrices) === 1 || (count($sellingPrices) === 2 && $sellingPrices[1] == "")) {
                $values[ProductFiltersEnum::SELLING_PRICE->value] = [
                    $sellingPrices[0],
                    $sellingPrices[0]
                ];
            } else {
                $values[ProductFiltersEnum::SELLING_PRICE->value] = $sellingPrices;
            }
        }

        // Quantity
        if ($this->has(ProductFiltersEnum::QUANTITIES->value) && $this->filled(ProductFiltersEnum::QUANTITIES->value)) {
            $quantities = explode("-", $this->get(ProductFiltersEnum::QUANTITIES->value));
            if (count($quantities) === 1 || (count($quantities) === 2 && $quantities[1] == "")) {
                $values[ProductFiltersEnum::QUANTITIES->value] = [
                    $quantities[0],
                    $quantities[0]
                ];
            } else {
                $values[ProductFiltersEnum::QUANTITIES->value] = $quantities;
            }
        }

        $this->merge($values);
    }
}

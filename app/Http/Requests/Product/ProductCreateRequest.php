<?php

namespace App\Http\Requests\Product;

use App\Enums\Product\ProductFieldsEnum;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ProductFieldsEnum::CATEGORY_ID->value   => [
                "bail",
                "required",
                "integer",
                Rule::exists((new Category())->getTable(), 'id')
            ],
            ProductFieldsEnum::SUPPLIER_ID->value   => [
                "bail",
                "required",
                "integer",
                Rule::exists((new Supplier())->getTable(), 'id')
            ],
            ProductFieldsEnum::NAME->value          => ["required", "string", "max:255"],
            ProductFieldsEnum::DESCRIPTION->value   => ["nullable", "string"],
            ProductFieldsEnum::PRODUCT_CODE->value  => ["required", "string", "max:255"],
            ProductFieldsEnum::ROOT->value          => ["required", "string", "max:255"],
            ProductFieldsEnum::BUYING_PRICE->value  => ["nullable", "numeric"],
            ProductFieldsEnum::SELLING_PRICE->value => ["required", "numeric", "gt:0"],
            ProductFieldsEnum::BUYING_DATE->value   => ["nullable", "date"],
            ProductFieldsEnum::QUANTITY->value      => ["required", "integer", "gt:0"],
            ProductFieldsEnum::PHOTO->value         => ["required", "file", "mimes:jpg,jpeg,png,gif,svg", "max:1024"],
        ];
    }
}

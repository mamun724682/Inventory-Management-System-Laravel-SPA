<?php

namespace App\Http\Requests\Supplier;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Supplier\SupplierFieldsEnum;
use App\Enums\Supplier\SupplierSortFieldsEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class SupplierIndexRequest extends BaseIndexRequest
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
            "name"      => ["nullable", "max:255"],
            "email"     => ["nullable", "max:100"],
            "phone"     => ["nullable", "max:255"],
            "shop_name" => ["nullable", "max:100"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(SupplierFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(SupplierSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }
}

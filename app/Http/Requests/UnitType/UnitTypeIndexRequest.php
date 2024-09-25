<?php

namespace App\Http\Requests\UnitType;

use App\Enums\Category\CategoryFieldsEnum;
use App\Enums\Category\CategorySortFieldsEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\UnitType\UnitTypeFiltersEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class UnitTypeIndexRequest extends BaseIndexRequest
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
            UnitTypeFiltersEnum::NAME->value   => ["nullable", "max:255"],
            UnitTypeFiltersEnum::SYMBOL->value => ["nullable", "max:255"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(CategoryFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(CategorySortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }
}

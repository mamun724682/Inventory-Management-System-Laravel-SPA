<?php

namespace App\Http\Requests\User;

use App\Enums\Core\SortOrderEnum;
use App\Enums\User\UserFieldsEnum;
use App\Enums\User\UserFiltersEnum;
use App\Enums\User\UserSortFieldsEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class UserIndexRequest extends BaseIndexRequest
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
            UserFiltersEnum::NAME->value  => ["nullable", "max:255"],
            UserFiltersEnum::EMAIL->value => ["nullable", "email", "max:255"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(UserFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(UserSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }
}

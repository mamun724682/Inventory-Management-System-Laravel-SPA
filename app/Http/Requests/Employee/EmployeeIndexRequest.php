<?php

namespace App\Http\Requests\Employee;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Employee\EmployeeFieldsEnum;
use App\Enums\Employee\EmployeeFiltersEnum;
use App\Enums\Employee\EmployeeSortFieldsEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class EmployeeIndexRequest extends BaseIndexRequest
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
            EmployeeFiltersEnum::NAME->value                => ["nullable", "max:255"],
            EmployeeFiltersEnum::EMAIL->value               => ["nullable", "max:100"],
            EmployeeFiltersEnum::PHONE->value               => ["nullable", "max:255"],
            EmployeeFiltersEnum::DESIGNATION->value         => ["nullable", "max:255"],
            EmployeeFiltersEnum::NID->value                 => ["nullable", "max:100"],
            EmployeeFiltersEnum::SALARY->value              => ["nullable", "array", "min:2", "max:2"],
            EmployeeFiltersEnum::SALARY->value . '.*'       => ["required", "numeric"],
            EmployeeFiltersEnum::JOINING_DATE->value        => ["nullable", "array", "min:2", "max:2"],
            EmployeeFiltersEnum::JOINING_DATE->value . '.*' => ["required", "date"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(EmployeeFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(EmployeeSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }

    protected function rangeFilters(): array
    {
        return [
            EmployeeFiltersEnum::SALARY->value,
        ];
    }
}

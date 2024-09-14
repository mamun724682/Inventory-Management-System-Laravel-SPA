<?php

namespace App\Http\Requests\Salary;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Salary\SalaryFieldsEnum;
use App\Enums\Salary\SalaryFiltersEnum;
use App\Enums\Salary\SalarySortFieldsEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class SalaryIndexRequest extends BaseIndexRequest
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
            SalaryFiltersEnum::EMPLOYEE_ID->value   => ["nullable", "string", "max:255"],
            SalaryFiltersEnum::AMOUNT->value        => ["nullable", "array", "min:2", "max:2"],
            SalaryFiltersEnum::AMOUNT->value . ".*" => ["required", "numeric"],
            SalaryFiltersEnum::SALARY_DATE->value   => ["nullable", "date"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(SalaryFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(SalarySortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }

    protected function rangeFilters(): array
    {
        return [
            SalaryFiltersEnum::AMOUNT->value
        ];
    }
}

<?php

namespace App\Http\Requests\Expense;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Expense\ExpenseFieldsEnum;
use App\Enums\Expense\ExpenseFiltersEnum;
use App\Enums\Expense\ExpenseSortFieldsEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class ExpenseIndexRequest extends BaseIndexRequest
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
            ExpenseFiltersEnum::NAME->value                => ["nullable", "string", "max:255"],
            ExpenseFiltersEnum::AMOUNT->value              => ["nullable", "array", "min:2", "max:2"],
            ExpenseFiltersEnum::AMOUNT->value . ".*"       => ["required", "numeric"],
            ExpenseFiltersEnum::EXPENSE_DATE->value        => ["nullable", "array", "min:2", "max:2"],
            ExpenseFiltersEnum::EXPENSE_DATE->value . ".*" => ["required", "date"],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(ExpenseFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(ExpenseSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }

    protected function rangeFilters(): array
    {
        return [
            ExpenseFiltersEnum::AMOUNT->value
        ];
    }
}

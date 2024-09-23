<?php

namespace App\Http\Requests\Transaction;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Transaction\TransactionExpandEnum;
use App\Enums\Transaction\TransactionFieldsEnum;
use App\Enums\Transaction\TransactionFiltersEnum;
use App\Enums\Transaction\TransactionPaidThroughEnum;
use App\Enums\Transaction\TransactionSortFieldsEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class TransactionIndexRequest extends BaseIndexRequest
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
            TransactionFiltersEnum::TRANSACTION_NUMBER->value => ["nullable", "integer"],
            TransactionFiltersEnum::ORDER_ID->value           => ["nullable", "max:255"],
            TransactionFiltersEnum::AMOUNT->value             => ["nullable", "array", "min:2", "max:2"],
            TransactionFiltersEnum::AMOUNT->value . ".*"      => ["required", "numeric", "min:0"],
            TransactionFiltersEnum::PAID_THROUGH->value       => ["nullable", "string", Rule::in(TransactionPaidThroughEnum::values())],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(TransactionFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(TransactionSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "expand"       => ["nullable", "array"],
            "expand.*"     => ["required", "string", Rule::in(TransactionExpandEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }

    protected function rangeFilters(): array
    {
        return [
            TransactionFiltersEnum::AMOUNT->value,
        ];
    }
}

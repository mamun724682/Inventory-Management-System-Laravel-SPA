<?php

namespace App\Http\Requests\Order;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Order\OrderExpandEnum;
use App\Enums\Order\OrderFieldsEnum;
use App\Enums\Order\OrderFiltersEnum;
use App\Enums\Order\OrderSortFieldsEnum;
use App\Enums\Order\OrderStatusEnum;
use App\Http\Requests\BaseIndexRequest;
use Illuminate\Validation\Rule;

class OrderIndexRequest extends BaseIndexRequest
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
            OrderFiltersEnum::CUSTOMER_ID->value      => ["nullable", "integer"],
            OrderFiltersEnum::ORDER_NUMBER->value     => ["nullable", "max:255"],
            OrderFiltersEnum::SUB_TOTAL->value        => ["nullable", "array", "min:2", "max:2"],
            OrderFiltersEnum::SUB_TOTAL->value . ".*" => ["required", "numeric", "min:0"],
            OrderFiltersEnum::TOTAL->value            => ["nullable", "array", "min:2", "max:2"],
            OrderFiltersEnum::TOTAL->value . ".*"     => ["required", "numeric", "min:0"],
            OrderFiltersEnum::DUE->value              => ["nullable", "array", "min:2", "max:2"],
            OrderFiltersEnum::DUE->value . ".*"       => ["required", "numeric", "min:0"],
            OrderFiltersEnum::PROFIT->value           => ["nullable", "array", "min:2", "max:2"],
            OrderFiltersEnum::PROFIT->value . ".*"    => ["required", "numeric", "min:0"],
            OrderFiltersEnum::LOSS->value             => ["nullable", "array", "min:2", "max:2"],
            OrderFiltersEnum::LOSS->value . ".*"      => ["required", "numeric", "min:0"],
            OrderFiltersEnum::STATUS->value           => ["nullable", "string", Rule::in(OrderStatusEnum::values())],

            "created_at"   => ["nullable", "array", "min:2", "max:2"],
            "created_at.*" => ["nullable", "date_format:Y-m-d H:i:s"],
            "fields"       => ["nullable", "array"],
            "fields.*"     => ["nullable", Rule::in(OrderFieldsEnum::values())],
            "sort_by"      => ["nullable", Rule::in(OrderSortFieldsEnum::values())],
            "sort_order"   => ["nullable", Rule::in(SortOrderEnum::values())],
            "expand"       => ["nullable", "array"],
            "expand.*"     => ["required", "string", Rule::in(OrderExpandEnum::values())],
            "page"         => ["nullable", "integer", "min:1"],
            "per_page"     => ["nullable", "integer", "min:1"],
        ];
    }

    protected function rangeFilters(): array
    {
        return [
            OrderFiltersEnum::SUB_TOTAL->value,
            OrderFiltersEnum::TOTAL->value,
            OrderFiltersEnum::DUE->value,
            OrderFiltersEnum::PROFIT->value,
            OrderFiltersEnum::LOSS->value,
        ];
    }
}

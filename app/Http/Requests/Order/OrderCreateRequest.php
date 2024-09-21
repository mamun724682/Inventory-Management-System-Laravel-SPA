<?php

namespace App\Http\Requests\Order;

use App\Enums\Core\AmountTypeEnum;
use App\Enums\Order\OrderFieldsEnum;
use App\Enums\Transaction\TransactionPaidThroughEnum;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
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
            OrderFieldsEnum::CUSTOMER_ID->value => [
                "bail",
                "nullable",
                "integer",
                Rule::exists((new Customer())->getTable(), 'id')
            ],
            OrderFieldsEnum::PAID->value        => ["nullable", "numeric"],
            "paid_through"                      => ["required", "string", Rule::in(TransactionPaidThroughEnum::values())],
            "custom_discount"                   => ["nullable", "array"],
            "custom_discount.discount"          => ["required_with:custom_discount", "numeric", "gte:0"],
            "custom_discount.discount_type"     => ["required_with:custom_discount", "string", Rule::in(AmountTypeEnum::values())],
        ];
    }
}

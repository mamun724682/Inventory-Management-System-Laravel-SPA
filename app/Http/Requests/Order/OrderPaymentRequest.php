<?php

namespace App\Http\Requests\Order;

use App\Enums\Transaction\TransactionFieldsEnum;
use App\Enums\Transaction\TransactionPaidThroughEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderPaymentRequest extends FormRequest
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
            TransactionFieldsEnum::AMOUNT->value       => ["required", "numeric"],
            TransactionFieldsEnum::PAID_THROUGH->value => ["required", "string", Rule::in(TransactionPaidThroughEnum::values())]
        ];
    }
}

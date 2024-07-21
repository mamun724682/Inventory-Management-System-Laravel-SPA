<?php

namespace App\Http\Requests\Expense;

use App\Enums\Expense\ExpenseFieldsEnum;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
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
            ExpenseFieldsEnum::NAME->value         => ["required", "string", "max:255"],
            ExpenseFieldsEnum::DESCRIPTION->value  => ["nullable", "string"],
            ExpenseFieldsEnum::AMOUNT->value       => ["required", "numeric"],
            ExpenseFieldsEnum::EXPENSE_DATE->value => ["required", "date"],
        ];
    }
}

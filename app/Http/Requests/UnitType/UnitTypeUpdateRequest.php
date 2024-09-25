<?php

namespace App\Http\Requests\UnitType;

use App\Enums\UnitType\UnitTypeFieldsEnum;
use App\Models\UnitType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitTypeUpdateRequest extends FormRequest
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
            UnitTypeFieldsEnum::NAME->value => [
                "required",
                "string",
                "max:50",
                Rule::unique((new UnitType())->getTable())->ignore($this->unit_type)
            ],
            UnitTypeFieldsEnum::SYMBOL->value => [
                "required",
                "string",
                "max:3",
                Rule::unique((new UnitType())->getTable())->ignore($this->unit_type)
            ],
        ];
    }
}

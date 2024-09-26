<?php

namespace App\Http\Requests\Setting;

use App\Enums\Setting\SettingFieldsEnum;
use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            SettingFieldsEnum::DECIMAL_POINT->value   => ["nullable", "integer", "max:8"],
            SettingFieldsEnum::DISCOUNT->value        => ["nullable", "numeric", "gte:0"],
            SettingFieldsEnum::TAX->value             => ["nullable", "numeric", "gte:0"],
            SettingFieldsEnum::CURRENCY_SYMBOL->value => ["nullable", "string"],
        ];
    }
}

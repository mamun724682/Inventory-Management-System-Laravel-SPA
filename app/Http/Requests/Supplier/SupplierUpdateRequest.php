<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
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
            "name"      => ["required", "string", "max:255"],
            "email"     => ["required", "string", "max:255"],
            "phone"     => ["required", "string", "max:255"],
            "address"   => ["nullable", "string"],
            "photo"     => ["nullable", "string"],
            "shop_name" => ["nullable", "string", "max:255"],
        ];
    }
}

<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerCreateRequest extends FormRequest
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
            "name"    => ["required", "string", "max:255"],
            "email"   => [
                "required",
                "email",
                "max:255",
                Rule::unique((new Customer())->getTable())
            ],
            "phone"   => ["required", "string", "max:255"],
            "address" => ["nullable", "string"],
            "photo"   => ["nullable", "file", "mimes:jpg,jpeg,png,gif,svg", "max:1024"],
        ];
    }
}

<?php

namespace App\Http\Requests\Employee;

use App\Enums\Employee\EmployeeFieldsEnum;
use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
            EmployeeFieldsEnum::NAME->value         => ["required", "string", "max:255"],
            EmployeeFieldsEnum::EMAIL->value        => [
                "required",
                "email",
                Rule::unique((new Employee())->getTable())->ignore($this->employee)
            ],
            EmployeeFieldsEnum::PHONE->value        => ["required", "string", "max:255"],
            EmployeeFieldsEnum::DESIGNATION->value  => ["nullable", "string", "max:255"],
            EmployeeFieldsEnum::ADDRESS->value      => ["required", "string"],
            EmployeeFieldsEnum::SALARY->value       => ["required", "numeric"],
            EmployeeFieldsEnum::PHOTO->value        => ["nullable", "file", "mimes:jpg,jpeg,png,gif,svg", "max:1024"],
            EmployeeFieldsEnum::NID->value          => ["nullable", "string", "max:255"],
            EmployeeFieldsEnum::JOINING_DATE->value => ["required", "date", "max:255"],
        ];
    }
}

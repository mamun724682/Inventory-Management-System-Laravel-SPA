<?php

namespace App\Http\Requests\Salary;

use App\Enums\Salary\SalaryFieldsEnum;
use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaryCreateRequest extends FormRequest
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
            SalaryFieldsEnum::EMPLOYEE_ID->value => [
                "bail",
                "required",
                "integer",
                Rule::exists((new Employee())->getTable(), 'id'),
            ],
            SalaryFieldsEnum::SALARY_DATE->value => ["required", "date"],
        ];
    }
}

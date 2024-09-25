<?php

namespace App\Http\Requests\Category;

use App\Enums\Category\CategoryFieldsEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryCreateRequest extends FormRequest
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
            CategoryFieldsEnum::NAME->value => [
                "required",
                "string",
                "max:255",
                Rule::unique((new Category())->getTable())
            ],
        ];
    }
}

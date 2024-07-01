<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    protected function prepareForValidation()
    {
        $values = [];

        if ($this->has("fields")) {
            $values["fields"][] = "id";
            if ($this->filled("fields")) {
                $values["fields"] = array_merge(
                    $values["fields"],
                    explode(",", $this->get("fields"))
                );
            }
            $values["fields"] = array_unique($values["fields"]);
        }

        if ($this->has("expand") && $this->filled("expand")) {
            $values["expand"] = explode(",", $this->get("expand"));
        }

        $this->merge($values);
    }
}

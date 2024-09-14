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

        if (method_exists($this, 'rangeFilters')) {
            $rangeFilters = $this->rangeFilters();
            foreach ($rangeFilters as $rangeFilter) {
                if ($this->has($rangeFilter) && $this->filled($rangeFilter)) {
                    $ranges = explode("-", $this->get($rangeFilter));
                    if (count($ranges) === 1 || (count($ranges) === 2 && $ranges[1] == "")) {
                        $values[$rangeFilter] = [
                            $ranges[0],
                            $ranges[0]
                        ];
                    } else {
                        $values[$rangeFilter] = $ranges;
                    }
                }
            }
        }

        $this->merge($values);
    }
}

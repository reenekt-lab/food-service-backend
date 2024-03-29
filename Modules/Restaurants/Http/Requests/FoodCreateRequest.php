<?php

namespace Modules\Restaurants\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'categories' => ['sometimes', 'array'],
            'tags' => ['sometimes', 'array'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

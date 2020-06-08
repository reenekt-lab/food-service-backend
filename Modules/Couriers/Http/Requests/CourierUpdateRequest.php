<?php

namespace Modules\Couriers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class CourierUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'surname' => ['sometimes', 'required', 'string', 'max:255'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone_number' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('couriers')->ignore($this->courier->id)],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        if (App::isLocale('ru')) {
            return [
                'surname.required' => __('couriers::courier_request_messages.surname.required'),
                'first_name.required' => __('couriers::courier_request_messages.first_name.required'),
                'middle_name.required' => __('couriers::courier_request_messages.middle_name.required'),
                'phone_number.required' => __('couriers::courier_request_messages.phone_number.required'),
                'email.required' => __('couriers::courier_request_messages.email.required'),
                'password.required' => __('couriers::courier_request_messages.password.required'),

                'surname.string' => __('couriers::courier_request_messages.surname.string'),
                'first_name.string' => __('couriers::courier_request_messages.first_name.string'),
                'middle_name.string' => __('couriers::courier_request_messages.middle_name.string'),
                'phone_number.string' => __('couriers::courier_request_messages.phone_number.string'),
                'email.string' => __('couriers::courier_request_messages.email.string'),
                'password.string' => __('couriers::courier_request_messages.password.string'),

                'surname.max' => __('couriers::courier_request_messages.surname.max'),
                'first_name.max' => __('couriers::courier_request_messages.first_name.max'),
                'middle_name.max' => __('couriers::courier_request_messages.middle_name.max'),
                'phone_number.max' => __('couriers::courier_request_messages.phone_number.max'),
                'email.max' => __('couriers::courier_request_messages.email.max'),
                'password.min' => __('couriers::courier_request_messages.password.min'),

                'email.email' => __('couriers::courier_request_messages.email.email'),
                'email.unique' => __('couriers::courier_request_messages.email.unique'),
                'password.confirmed' => __('couriers::courier_request_messages.password.confirmed'),

            ];
        }

        // Using default messages
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check()
            || auth('restaurant_manager')->check()
            || auth('courier')->check();
    }
}

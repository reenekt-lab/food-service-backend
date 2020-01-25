<?php

namespace Modules\Customers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class CustomerCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'surname' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
                'surname.required' => __('customers::customer_request_messages.surname.required'),
                'first_name.required' => __('customers::customer_request_messages.first_name.required'),
                'middle_name.required' => __('customers::customer_request_messages.middle_name.required'),
                'phone_number.required' => __('customers::customer_request_messages.phone_number.required'),
                'email.required' => __('customers::customer_request_messages.email.required'),
                'password.required' => __('customers::customer_request_messages.password.required'),

                'surname.string' => __('customers::customer_request_messages.surname.string'),
                'first_name.string' => __('customers::customer_request_messages.first_name.string'),
                'middle_name.string' => __('customers::customer_request_messages.middle_name.string'),
                'phone_number.string' => __('customers::customer_request_messages.phone_number.string'),
                'email.string' => __('customers::customer_request_messages.email.string'),
                'password.string' => __('customers::customer_request_messages.password.string'),

                'surname.max' => __('customers::customer_request_messages.surname.max'),
                'first_name.max' => __('customers::customer_request_messages.first_name.max'),
                'middle_name.max' => __('customers::customer_request_messages.middle_name.max'),
                'phone_number.max' => __('customers::customer_request_messages.phone_number.max'),
                'email.max' => __('customers::customer_request_messages.email.max'),
                'password.min' => __('customers::customer_request_messages.password.min'),

                'email.email' => __('customers::customer_request_messages.email.email'),
                'email.unique' => __('customers::customer_request_messages.email.unique'),
                'password.confirmed' => __('customers::customer_request_messages.password.confirmed'),

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
        return auth('api')->check();
    }
}

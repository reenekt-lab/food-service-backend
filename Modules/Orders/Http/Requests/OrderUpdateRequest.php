<?php

namespace Modules\Orders\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Modules\Orders\Entities\Order;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => ['sometimes', 'array' ],
            'restaurant_id' => ['sometimes', 'exists:restaurants,id'],
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'courier_id' => ['sometimes', 'nullable', 'exists:couriers,id'],
            'status' => [
                'sometimes', Rule::in([
                    Order::STATUS_CREATED,
                    Order::STATUS_PAID,
                    Order::STATUS_COOKING,
                    Order::STATUS_DELIVERING,
                    Order::STATUS_DELIVERED,
                ])
            ],
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
                'content.required' => __('orders::order_request_messages.content.required'),
                'restaurant_id.required' => __('orders::order_request_messages.restaurant_id.required'),
                'customer_id.required' => __('orders::order_request_messages.customer_id.required'),

                'content.array' => __('orders::order_request_messages.content.array'),

                'restaurant_id.exists' => __('orders::order_request_messages.restaurant_id.exists'),
                'customer_id.exists' => __('orders::order_request_messages.customer_id.exists'),
                'courier_id.exists' => __('orders::order_request_messages.courier_id.exists'),

                'status.in' => __('orders::order_request_messages.status.in'),
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
        return true;
    }
}

<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Modules\Customers\Entities\Customer;
use Modules\Orders\Entities\Order;
use Modules\Payments\Entities\Bill;
use Modules\Restaurants\Entities\Restaurant;

$factory->define(Bill::class, function (Faker $faker) {
    return [
        'customer_id' => factory(Customer::class),
        'restaurant_id' => factory(Restaurant::class),
        'order_id' => factory(Order::class),
        'amount' => 10,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Customers\Entities\Customer;
use Modules\Orders\Entities\Order;
use Modules\Restaurants\Entities\Restaurant;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'customer_id' => factory(Customer::class),
        'content' => [],
        'restaurant_id' => factory(Restaurant::class),
    ];
});

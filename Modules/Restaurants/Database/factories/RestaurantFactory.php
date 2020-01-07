<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Modules\Restaurants\Entities\Restaurant;

$factory->define(Restaurant::class, function (Faker $faker) {
    $name = $faker->word;
    if (rand(0, 1) > 0) {
        $name = 'Restaurant "' . $name . '"';
    }
    return [
        'name' => $name,
        'description' => $faker->realText(),
        'address' => $faker->address,
    ];
});

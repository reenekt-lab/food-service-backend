<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Entities\Restaurant;

$factory->define(Food::class, function (Faker $faker) {

    $cost = (double) rand(25, 10000);

    /*
     * в тестах не проходят проверку численные значения, только строковые
     * пример: в полученной записи cost => 1467.9
     *         в полученном ответе от сервера (API) cost => "1467.90"
     */
    if (app()->environment() == 'testing') {
        $cost = ((string) $cost) . '.' . str_pad((rand(0, 99)), 2, '0');
    }

    return [
        'name' => $faker->word,
        'description' => $faker->realText(),
        'cost' => $cost,
        'restaurant_id' => factory(Restaurant::class),
    ];
});

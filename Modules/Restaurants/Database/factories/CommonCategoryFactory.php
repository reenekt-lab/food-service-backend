<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Modules\Restaurants\Entities\CommonCategory;

$factory->define(CommonCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'image_url' => $faker->imageUrl(),
    ];
});

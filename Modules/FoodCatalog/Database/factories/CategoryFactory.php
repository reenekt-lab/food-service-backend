<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Modules\FoodCatalog\Entities\Category;

$factory->define(Category::class, function (Faker $faker) {
    $parent_id = null;
    // шанс 20% что категория будет дочерней
    if (rand(0, 10) > 7) {
        $parent_id = factory(Category::class);
    }
    return [
        'name' => $faker->word,
        'description' => $faker->realText(),
        'parent_id' => $parent_id,
    ];
});

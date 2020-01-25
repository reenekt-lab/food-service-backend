<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Modules\Customers\Entities\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'surname' => $faker->name,
        'first_name' => $faker->firstName,
        'middle_name' => $faker->name,
        'phone_number' => $faker->e164PhoneNumber,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//        'remember_token' => Str::random(10),
    ];
});

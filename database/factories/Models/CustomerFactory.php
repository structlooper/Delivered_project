<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'full_name' => $faker->regexify('[A-Za-z0-9]{255}'),
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'status' => $faker->numberBetween(-10000, 10000),
    ];
});

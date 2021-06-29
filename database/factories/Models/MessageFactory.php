<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->numberBetween(-10000, 10000),
        'phone' => $faker->phoneNumber,
        'title' => $faker->sentence(4),
        'message' => $faker->word,
    ];
});

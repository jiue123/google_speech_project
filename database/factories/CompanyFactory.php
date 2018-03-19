<?php

use App\Models\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'email' => $faker->companyEmail,
        'alias' => $faker->unique()->word,
    ];
});

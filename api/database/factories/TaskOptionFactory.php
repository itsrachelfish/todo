<?php

use Faker\Generator as Faker;
use App\Models\TaskOption;

$factory->define(TaskOption::class, function (Faker $faker)
{
    // Create valid task options
    $keys = ['deadline', 'recurring', 'recurring monthly', 'recurring annually'];
    $values =
    [
        'deadline' => $faker->date(),
        'recurring' => $faker->randomElement(['daily', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
        'recurring monthly' => $faker->numberBetween(1, 28),
        'recurring annually' => $faker->date('m-d')
    ];

    $randomKey = $faker->randomElement($keys);
    $randomValue = $values[$randomKey];

    return
    [
        'key' => $randomKey,
        'value' => $randomValue,
    ];
});

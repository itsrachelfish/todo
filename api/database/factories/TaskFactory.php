<?php

use Faker\Generator as Faker;
use App\Models\Task;

$factory->define(Task::class, function (Faker $faker)
{
    $statuses = ['', 'in progress', 'complete', 'deleted'];
    $types = ['one time', 'recurring', 'note'];

    return
    [
        'status' => $faker->randomElement($statuses),
        'type' => $faker->randomElement($types),
        'description' => $faker->bs,
    ];
});

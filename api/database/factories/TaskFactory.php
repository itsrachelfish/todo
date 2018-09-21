<?php

use Faker\Generator as Faker;
use App\Models\Task;

$factory->define(Task::class, function (Faker $faker)
{
    $statuses = ['', 'in progress', 'complete'];
    $types = ['one time', 'recurring', 'note'];

    return
    [
        'status' => $statuses[array_rand($statuses)],
        'type' => $types[array_rand($types)],
        'description' => $faker->bs,
    ];
});

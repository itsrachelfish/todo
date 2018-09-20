<?php

use Faker\Generator as Faker;
use App\Models\Project;

$factory->define(Project::class, function (Faker $faker)
{
    return
    [
        'name' => $faker->unique()->jobTitle,
        'description' => $faker->paragraphs(2, true),
    ];
});

<?php

use Faker\Generator as Faker;
use App\Models\Status;

/* Todo: Use factory states to test for the following cases:
 *
 * - No duration, no started at, no ended at
 * - Has duration but nothing else
 * - Has started at and duration
 * - Has started at and ended at (with computed duration)
 *
 * https://laravel.com/docs/5.5/database-testing#factory-states
 */

$factory->define(Status::class, function (Faker $faker)
{
    return [];
});

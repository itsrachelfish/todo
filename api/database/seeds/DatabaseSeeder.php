<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's with test data database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BasicTestSeeder::class);
    }
}

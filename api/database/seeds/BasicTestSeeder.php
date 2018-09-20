<?php

use Illuminate\Database\Seeder;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskOption;
use App\Models\Status;

class BasicTestSeeder extends Seeder
{
    private function createAndEcho($class, $output, $quantity = 1)
    {
        // A factory produces products
        $products = factory($class, $quantity)->create();

        foreach($products as $product)
        {
            echo "Created " . $class . ": " . $product->{$output} . "\n";
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAndEcho(Project::class, 'name', 3);
    }
}

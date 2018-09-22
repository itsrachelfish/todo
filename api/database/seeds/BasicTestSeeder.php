<?php

use Illuminate\Database\Seeder;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskOption;
use App\Models\Status;

class BasicTestSeeder extends Seeder
{
    private function createAndEcho($class, $output, $quantity = 1, $options = [])
    {
        // A factory produces products
        $products = factory($class, $quantity)->create($options);

        foreach($products as $product)
        {
            echo "Created " . $class . ": " . $product->{$output} . "\n";
        }

        return $products;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Creating projects:\n";
        $projects = $this->createAndEcho(Project::class, 'name', 3);

        foreach($projects as $project)
        {
            echo "Creating tasks for project '{$project->name}'\n";
            $tasks = $this->createAndEcho(Task::class, 'description', 5, ['project_id' => $project->id]);
        }

        echo "Creating tasks not associated with a project\n";
        $tasks = $this->createAndEcho(Task::class, 'description', 5);
    }
}

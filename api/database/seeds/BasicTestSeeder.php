<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

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

    private function createProjects()
    {
        echo "Creating projects:\n";
        $projects = $this->createAndEcho(Project::class, 'name', 3);

        return $projects;
    }

    private function createTasks($projects)
    {
        $tasks = new Collection;

        foreach($projects as $project)
        {
            echo "Creating tasks for project '{$project->name}'\n";
            $tasks = $tasks->concat($this->createAndEcho(Task::class, 'description', 5, ['project_id' => $project->id]));
        }

        echo "Creating tasks not associated with a project\n";
        $tasks = $tasks->concat($this->createAndEcho(Task::class, 'description', 5));

        return $tasks;
    }

    private function createChildTasks($tasks)
    {
        $childTasks = new Collection;

        echo "Creating random child tasks\n";
        foreach($tasks as $task)
        {
            // Only create child tasks for roughly a quarter of the tasks
            if(random_int(0, 3) === 1)
            {
                $childTasks = $childTasks->concat($this->createAndEcho(Task::class, 'description', random_int(1, 3), ['parent_id' => $task->id, 'project_id' => $task->project_id]));
            }
        }

        // Always generate at least one child task
        if($childTasks->isEmpty())
        {
            $childTasks = $childTasks->concat($this->createAndEcho(Task::class, 'description', random_int(1, 3), ['parent_id' => $tasks[0]->id, 'project_id' => $tasks[0]->project_id]));
        }

        return $childTasks;
    }

    private function createTaskOptions($tasks)
    {
        $taskOptions = new Collection;

        echo "Creating random task options\n";
        foreach($tasks as $task)
        {
            // Only create options for roughly half of the tasks
            if(random_int(0, 1) === 1)
            {
                $taskOptions = $taskOptions->concat($this->createAndEcho(TaskOption::class, 'key', 1, ['task_id' => $task->id]));
            }
        }

        // Always generate at least one task option
        if($taskOptions->isEmpty())
        {
            $taskOptions = $taskOptions->concat($this->createAndEcho(TaskOption::class, 'key', 1, ['task_id' => $tasks[0]->id]));
        }

        return $taskOptions;
    }

    private function createStatuses($tasks)
    {
        $statuses = new Collection;

        // Create a status entry for every task
        foreach($tasks as $task)
        {
             $statuses->concat($this->createAndEcho(Status::class, 'status', 1, ['task_id' => $task->id, 'status' => $task->status]));
        }

        return $statuses;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = $this->createProjects();
        $tasks = $this->createTasks($projects);

        $this->createChildTasks($tasks);
        $this->createTaskOptions($tasks);
        $this->createStatuses($tasks);
    }
}

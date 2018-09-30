<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskOption;
use App\Models\Status;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * A basic test for the database seeder.
     *
     * @return void
     */
    public function testSeeder()
    {
        // Check that projects exist
        $this->assertNotEmpty(Project::get());

        // Check that projects have tasks
        $this->assertNotEmpty(Project::get()->random()->tasks());

        // Check that tasks exist
        $this->assertNotEmpty(Task::get());

        // Check that tasks belong to projects
        $this->assertNotEmpty(Task::get()->random()->project());

        // Check that child tasks exist
        $this->assertNotEmpty(Task::has('children')->get());

        // Check that child tasks have parents
        $this->assertNotEmpty(Task::has('parent')->get());

        // Check that tasks have options
        $this->assertNotEmpty(Task::has('options')->get());

        // Check that tasks have statuses
        $this->assertNotEmpty(Task::has('statuses')->get());

        // Check that task options exist
        $this->assertNotEmpty(TaskOption::get());

        // Check that task options belong to tasks
        $this->assertNotEmpty(TaskOption::get()->random()->task());

        // Check that statuses exist
        $this->assertNotEmpty(Status::get());

        // Check that statuses belong to tasks
        $this->assertNotEmpty(Status::get()->random()->task());
    }
}

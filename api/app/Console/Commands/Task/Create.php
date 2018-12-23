<?php

namespace App\Console\Commands\Task;

use Illuminate\Console\Command;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:task:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new task via API request';

    /**
     * A guzzle object for making API requests
     *
     * @var GuzzleHttp\Client
     */
    private $api;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->api = new \GuzzleHttp\Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $description = $this->ask('[Required] A short description of your task');

//        $hasParent = $this->confirm("Does this task a sub-task?");
//        $hasProject = $this->confirm("Does this task belong to a project?");

        $type = $this->choice("What type of task is this?", ['one time', 'recurring', 'note']);

        $response = $this->api->post(env('APP_URL') . '/api/task',
        [
            'form_params' =>
            [
                'status' => 'new',
                'type' => $type,
                'description' => $description,
            ]
        ]);

        dump($response->getBody()->getContents());
    }
}

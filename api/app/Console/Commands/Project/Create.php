<?php

namespace App\Console\Commands\Project;

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
    protected $signature = 'todo:project:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new project via API request';

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
        $name = $this->ask("[Required] What's the name of your project?");
        $description = $this->ask('[Optional] A short description of your project');

        $response = $this->api->post(env('APP_URL') . '/api/project',
        [
            'form_params' =>
            [
                'name' => $name,
                'description' => $description,
            ]
        ]);

        dump($response->getBody()->getContents());
    }
}

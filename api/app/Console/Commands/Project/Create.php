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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api = new \GuzzleHttp\Client();
        $response = $api->post(env('APP_URL') . '/api/project',
        [
            'form_params' =>
            [
                'name' => 'Test project',
                'description' => 'Yay!'
            ]
        ]);

        dump($response->getBody()->getContents());
    }
}

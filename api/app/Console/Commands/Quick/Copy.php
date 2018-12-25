<?php

namespace App\Console\Commands\Quick;

use Illuminate\Console\Command;

class Copy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quick:copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick todo list - Copy todo.md file';

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
        $todoFile = env('TODO_PATH') . env('TODO_FILE');
        $datedFile = env('TODO_PATH') . date('Y-m-d') . '.md';
        $todoData = file_get_contents($todoFile);
        file_put_contents($datedFile, $todoData);

        $this->info("File {$datedFile} created.");
    }
}

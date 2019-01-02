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
    protected $signature = 'quick:copy {--prune : Remove completed tasks from current todo file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy todo.md file to dated file';

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
        $prune = $this->option('prune');
        $todoFile = env('TODO_PATH') . env('TODO_FILE');
        $datedFile = env('TODO_PATH') . date('Y-m-d') . '.md';
        $todoData = file_get_contents($todoFile);

        file_put_contents($datedFile, $todoData);
        $this->info("File {$datedFile} created.");

        if($prune)
        {
            $inputLines = explode("\n", $todoData);
            $outputLines = [];

            // Check each line of the input file for completed tasks
            foreach($inputLines as $line)
            {
                if(preg_match("/^\s*- \[x\]/", $line))
                {
                    $this->info("Pruning completed task: $line");
                }
                elseif(preg_match("/^\s*- \[NOPE\]/i", $line))
                {
                    $this->info("Pruning deleted task: $line");
                }
                else
                {
                    $outputLines[] = $line;
                }
            }

            // Recombine input file
            $output = implode("\n", $outputLines);
            file_put_contents($todoFile, $output);
        }
    }
}

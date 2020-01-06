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
        // Get list of files in todo directory
        $todoFiles = scandir(env('TODO_PATH'));

        foreach($todoFiles as $file)
        {
            // Ignore directories
            if(is_file(env('TODO_PATH') . $file))
            {
                $fileInfo = pathinfo(env('TODO_PATH') . $file);
                $this->saveFile(env('TODO_PATH'), $fileInfo['filename']);
            }
        }
    }

    private function saveFile($path, $filename)
    {
        $prune = $this->option('prune');
        $file = "{$path}{$filename}.md";
        $datedFile = "{$path}{$filename}/" . date('Y-m-d') . '.md';
        $fileData = file_get_contents($file);

        if(!file_exists("{$path}{$filename}"))
        {
            mkdir("{$path}{$filename}", 0755, true);
        }

        file_put_contents($datedFile, $fileData);
        $this->info("File {$datedFile} created.");

        if($prune)
        {
            $this->pruneFile($file, $fileData);
        }
    }

    private function pruneFile($file, $fileData)
    {
        $inputLines = explode("\n", $fileData);
        $outputLines = [];
        $removing = false;
        $indentation = 0;

        // Check each line of the input file for completed tasks
        foreach($inputLines as $line)
        {
            if(preg_match("/^(\s*)- \[x\]/", $line, $match))
            {
                $this->info("Pruning completed task: $line");
                $removing = true;
                $indentation = strlen($match[1]);
            }
            elseif(preg_match("/^(\s*)- \[NOPE\]/i", $line, $match))
            {
                $this->info("Pruning deleted task: $line");
                $removing = true;
                $indentation = strlen($match[1]);
            }
            elseif(preg_match("/^(\s*)- /i", $line, $match))
            {
                // Remove this line if it was indented more than the previous lines (it's a comment / sub-task)
                if($indentation >= strlen($match[1]))
                {
                    $removing = false;
                }

                if($removing)
                {
                    $this->info("Pruning comment / sub-task: $line");
                }
                else
                {
                    $outputLines[] = $line;
                }
            }
            else
            {
                $outputLines[] = $line;
                $removing = false;
            }
        }

        // Recombine input file
        $output = implode("\n", $outputLines);
        file_put_contents($file, $output);
    }
}

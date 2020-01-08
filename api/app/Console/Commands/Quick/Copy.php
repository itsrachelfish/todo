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

        // Create a directory for this file if necessary
        if(!file_exists("{$path}{$filename}"))
        {
            mkdir("{$path}{$filename}", 0755, true);
        }

        // Process the file to extract metadata and replace keywords
        $processedFile = $this->processFile($fileData);

        // Back up contents of todo file
        file_put_contents($datedFile, $processedFile['data']);
        $this->info("File {$datedFile} created.");

        if($prune)
        {
            // Prune completed tasks when the prune option is set
            $this->pruneFile($file, $processedFile['data']);
        }
        elseif($fileData != $processedFile['data'])
        {
            // Rewrite the input file when a change has been made
            file_put_contents($file, $processedFile['data']);
        }
    }

    private function processFile($fileData)
    {
        $fileLines = explode("\n", $fileData);
        $outputLines = [];
        $metadata = '';

        foreach($fileLines as $line)
        {
            if(preg_match("/^(#+ )today$/i", $line, $match))
            {
                $line = $match[1] . date('l, F jS, Y');
            }

            $outputLines[] = $line;
        }

        return [
            'data' => implode("\n", $outputLines),
            'metadata' => $metadata,
        ];
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

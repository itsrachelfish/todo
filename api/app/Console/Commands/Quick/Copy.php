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

    private $path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->path = env('TODO_PATH');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get list of files in todo directory
        $todoFiles = scandir($this->path);

        foreach($todoFiles as $file)
        {
            // Ignore directories
            if(is_file($this->path . $file))
            {
                $fileInfo = pathinfo($this->path . $file);
                $this->saveFile($fileInfo['filename']);
            }
        }
    }

    private function saveFile($filename)
    {
        $prune = $this->option('prune');
        $file =  "{$this->path}{$filename}.md";
        $today = "{$this->path}{$filename}/" . date('Y-m-d') . '.md';
        $fileData = file_get_contents($file);

        if(!file_exists("{$this->path}{$filename}"))
        {
            // Create a directory for this file if necessary
            mkdir("{$this->path}{$filename}", 0755, true);
        }
        else
        {
            // Find the most recently created backup for this file
            $mostRecentBackup = scandir("{$this->path}{$filename}", SCANDIR_SORT_DESCENDING)[0];

            if(is_file("{$this->path}{$filename}/{$mostRecentBackup}"))
            {
                $backupData = file_get_contents("{$this->path}{$filename}/{$mostRecentBackup}");
            }
        }

        // Process the file to extract metadata and replace keywords
        $processedFile = $this->processFile($fileData);

        if($prune)
        {
            // Prune completed tasks when the prune option is set
            $processedFile['data'] = $this->pruneFile($file, $processedFile);
        }
        elseif($fileData != $processedFile['data'])
        {
            // Rewrite the input file when a change has been made
            file_put_contents($file, $processedFile['data']);
        }

        // If the current file is empty, skip creating a daily backup file
        if(empty(trim($processedFile['data'])) || trim($processedFile['data']) == json_encode($processedFile['metadata']))
        {
            $this->info("Empty file {$today} skipped.");
            return;
        }

        // If the current file is the same as the most recent backup, skip creating a new one
        if(isset($backupData) && $backupData == $processedFile['data'])
        {
            $this->info("Duplicate file {$today} skipped.");
            return;
        }

        // Create a daily back up of the file
        file_put_contents($today, $processedFile['data']);
        $this->info("File {$today} created.");
    }

    private function processFile($fileData)
    {
        $fileLines = explode("\n", $fileData);
        $outputLines = [];
        $metadata = false;

        foreach($fileLines as $lineNumber => $line)
        {
            if($lineNumber === 0)
            {
                $metadata = json_decode($line, true);
            }

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

    private function pruneFile($file, $processedFile)
    {
        $inputLines = explode("\n", $processedFile['data']);
        $outputLines = [];
        $removing = false;
        $indentation = 0;

        // If this file is processed weekly and it is not the last day of the week, skip pruning
        if(isset($processedFile['metadata']['frequency']) && $processedFile['metadata']['frequency'] == 'weekly' && date('w') != 6)
        {
            return;
        }

        // Check each line of the input file for completed tasks
        foreach($inputLines as $line)
        {
            // If the wipe option is being used
            if(isset($processedFile['metadata']['wipe']) && $processedFile['metadata']['wipe'] == true)
            {
                // Clear everything out of the file each time it gets pruned, except for its metadata
                $outputLines[] = json_encode($processedFile['metadata']);
                break;
            }

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

        return $output;
    }
}

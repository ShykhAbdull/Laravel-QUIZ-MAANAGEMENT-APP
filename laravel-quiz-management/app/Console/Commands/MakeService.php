<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command would make separate service for controllers';

       // Filesystem instance to interact with files
       protected $files;

       // Constructor
       public function __construct(Filesystem $files)
       {
           parent::__construct();
           $this->files = $files;
       }

    /**
     * Execute the console command.
     *
     * @return int
     */
// Execute the command
public function handle()
{
    $serviceName = $this->argument('name');

    $path = $this->getPath($serviceName);

    if ($this->files->exists($path)) {
        $this->error('Service already exists!');
        return false; // Indicate failure
    }

    $this->makeDirectory($path);

    // Create the service file
    $this->files->put($path, $this->buildClass($serviceName));

    $this->info('Service created successfully.');

    return true; // Indicate success
}


        // Get the service file path
        protected function getPath($name)
        {
            return base_path('app/Services') . '/' . $name . '.php';
        }

            // Create the directory if it doesn't exist
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }
    }

        // Build the service class content
        protected function buildClass($name)
        {
            $namespace = 'App\\Services';
    
            return <<<PHP
    <?php
    
    namespace {$namespace};
    
    class {$name}
    {
        //
    }
    PHP;
        }

}

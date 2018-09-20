<?php

namespace RepositoryPattern;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rp:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repository pattern setup';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance and filesystem instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        if (!$this->files->isDirectory($this->getRepositoriesPath())) {
            $this->files->makeDirectory($this->getRepositoriesPath());
        }

        foreach ($this->getFiles() as $file) {
            if (!$this->files->exists($this->getFilePath($file['name']))) {
                $this->files->put(
                    $this->getFilePath($file['name']),
                    $this->files->get(__DIR__ . '/../../stubs/' . $file['stub'] . '.stub')
                );
            } else {
                $this->error($file['name'] . ' already exists!');

                return false;
            }
        }

        $this->info('Repository pattern setup completed.');
    }

    /**
     * Get the repositories path.
     * 
     * @return string
     */
    public function getRepositoriesPath()
    {
        return app_path('Repositories');
    }

    /**
     * Get the setup files info.
     * 
     * @return array
     */
    public function getFiles()
    {
        return [
            ['name' => 'Repositoryable', 'stub' => 'repository.interface'],
            ['name' => 'Repository', 'stub' => 'repository.base']
        ];   
    }

    /**
     * Get the file path.
     * 
     * @param  string  $fileName
     * @return string
     */
    public function getFilePath($fileName)
    {
        return $this->getRepositoriesPath() . '/' . $fileName . '.php';
    }
}

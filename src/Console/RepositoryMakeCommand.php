<?php

namespace RepositoryPattern;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     * 
     * @var string
     */
    protected $name = 'rp:repository';

    /**
     * The console command description.
     * 
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     * 
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('controller') ||
            $this->option('resource') ||
            $this->option('api')) {
            $this->createController();
        }
    }

    /**
     * Create a controller for the repository.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = str_replace($this->type, '', $this->getNameInput());

        $this->call('rp:controller', [
            'name' => $this->option('api') ? 'API\\' . $controller : $controller,
            '-r' => $this->option('resource') ? true : false,
            '--api' => $this->option('api') ? true : false
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace(
            'DummyModel',
            str_replace($this->type, '', $this->getNameInput()),
            parent::buildClass($name)
        );
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim(ucfirst($this->argument('name'))) . $this->type;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a controller for the repository.'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Create a resource controller for the repository.'],
            ['api', null, InputOption::VALUE_NONE, 'Create an API controller for the repository.']
        ];
    }
}

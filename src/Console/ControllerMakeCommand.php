<?php

namespace RepositoryPattern;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     * 
     * @var string
     */
    protected $name = 'rp:controller';

    /**
     * The console command description.
     * 
     * @var string
     */
    protected $description = 'Create a controller for the repository';

    /**
     * The type of class being generated.
     * 
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('resource') || $this->option('api')) {
            $this->createRequest();
        }
    }

    /**
     * Create a request for the controller.
     * 
     * @return void
     */
    protected function createRequest()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $this->call('make:request', [
            'name' => str_replace('Controller', '', str_replace($this->getNamespace($name) . '\\', '', $name) . 'Request')
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('resource')) {
            $stubName = 'controller.resource';
        } elseif ($this->option('api')) {
            $stubName = 'controller.api';
        } else {
            $stubName = 'controller';
        }

        return __DIR__ . '/../../stubs/' . $stubName . '.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $inputName = str_replace(
            'Controller',
            '',
            str_replace($this->getNamespace($name) . '\\', '', $name)
        );

        return str_replace(
            ['DummyRepository', 'DummyInstance', 'DummyRequest'],
            [$inputName . 'Repository', strtolower($inputName), $inputName . 'Request'],
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
            ['resource', 'r', InputOption::VALUE_NONE, 'Create a resource controller for the repository.'],
            ['api', null, InputOption::VALUE_NONE, 'Create an API controller for the repository.']
        ];
    }
}

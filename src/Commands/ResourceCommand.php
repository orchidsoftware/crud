<?php

namespace Orchid\Crud\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ResourceCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orchid:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../../stubs/resource.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Orchid\Resources';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The model class being represented.'],
        ];
    }

    /**
     * @param string $model
     *
     * @return string
     */
    protected function detectModel(string $model): string
    {
        $rootNamespace = $this->laravel->getNamespace();

        $withFolder = $rootNamespace . 'Models\\' . $model;
        $withoutFolder = $rootNamespace . 'Models\\' . $model;

        if (class_exists($withFolder)) {
            return '\\' . $withFolder . '::class';
        }

        if (class_exists($withoutFolder)) {
            return '\\' . $withoutFolder . '::class';
        }

        return "''";
    }

    /**
     * @return string
     */
    public function detectModelForNameResource(): string
    {
        $detectModelForName = Str::of($this->argument('name'));

        if ($detectModelForName->endsWith('Resource')) {
            $detectModelForName = $detectModelForName->replaceLast('Resource', '');
        }

        return $this->detectModel($detectModelForName);
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $model = $this->option('model') === null
            ? $this->detectModelForNameResource()
            : $this->detectModel($this->option('model'));

        return Str::of(parent::buildClass($name))->replace('{{ namespacedModel }}', $model);
    }
}

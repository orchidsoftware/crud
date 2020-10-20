<?php

namespace Orchid\Crud\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Orchid\Crud\Builder\Migrations;
use Orchid\Crud\Resource;
use Illuminate\Support\Str;

class BuildCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orchid:resource:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build migration and model from resource';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * The resource.
     *
     * @var Resource
     */
    protected $resource;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer $composer
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->files = $files;
        $this->composer = app()['composer'];
    }


    public function handle()
    {
        $this->resource = $this->getResourceClass();
        if (is_null($this->resource)) {
            return ;
        }

        $this->makeMigration();
        /*
        $this->makeModel();

        $this->composer->dumpAutoloads();
        */
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/migration.stub';
    }

    /**
     * Get the resources class.
     *
     * @return Resource
     */
    protected function getResourceClass(): ?Resource
    {
        $name = $this->argument('name');
        $class = $this->getResourceNamespace($this->rootNamespace(),$name);

        if (!class_exists($class))
        {
            $this->line("<error>Not found class:</error> {$class}");
            return null;
        }

        $resource = new $class();
        if (!$resource instanceof Resource)
        {
            $this->line("<error>Class is not Resource:</error> {$class}");
            return null;
        }

        return $resource;
    }


    /**
     * Get the resources namespace.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getResourceNamespace($rootNamespace, $nameResource): string
    {
        return $rootNamespace.'Orchid\Resources\\'.$nameResource;
    }

    /**
     * Generate the desired migration.
     */
    protected function makeMigration()
    {
        $name = Str::snake($this->createTableName());
        if ($this->files->exists($path = $this->getPath($name))) {
            return $this->error($this->type . ' already exists!');
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->generateMigrationStub());

        $filename = pathinfo($path, PATHINFO_FILENAME);
        $this->line("<info>Created Migration:</info> {$filename}");
    }


    /**
     * Generate migration stub.
     */
    protected function generateMigrationStub()
    {
        $migration = Migrations::make($this->resource->fields());

        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceTable($stub, $this->argument('name'))
            ->replaceSlug($stub)
            ->replaceMigrations($stub, $migration->getMigration())
            ->replaceClass($stub, $this->createTableName());

        return $stub;
    }

    /**
     * Create migration class name
     * @return string
     */
    protected function createTableName()
    {
        return 'Create'.$this->argument('name').'Table';
    }

    /**
     * Replace table string in stub
     *
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceTable(&$stub, $name)
    {
        $stub = str_replace('{{ table }}', Str::snake($name), $stub);

        return $this;
    }

    /**
     * Replace slug string in stub
     *
     * @param $stub
     * @return $this
     */
    protected function replaceSlug(&$stub)
    {
        $slug = '';
        if (!is_null($this->resource->slug)) {
            $slug = '$table->string(\'slug\', \'255\')->unique();';
        }

        $stub = str_replace('{{ slug }}', $slug, $stub);

        return $this;
    }


    /**
     * Replace migrations string in stub
     *
     * @param $stub
     * @param $migrations
     * @return $this
     */
    protected function replaceMigrations(&$stub, $migrations)
    {
        $text = implode("\n" . str_repeat(' ', 12), $migrations);
        $stub = str_replace('{{ migrations }}', $text, $stub);

        return $this;
    }

    /**
     * Get migration path with filename
     * @param $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path().'/database/migrations/'.date('Y_m_d_His').'_'.$name.'.php';
    }

}

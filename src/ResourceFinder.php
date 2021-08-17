<?php

namespace Orchid\Crud;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\PathFilterIterator;
use Symfony\Component\Finder\SplFileInfo;

class ResourceFinder
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * IconFinder constructor.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
        $this->namespace = app()->getNamespace();
    }

    /**
     * @param string $namespace
     *
     * @return ResourceFinder
     */
    public function setNamespace(string $namespace): ResourceFinder
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $directory
     *
     * @return array
     */
    public function find(string $directory): array
    {
        try {
            $resources = $this->finder
                ->ignoreUnreadableDirs()
                ->followLinks()
                ->in($directory)
                ->files();
        } catch (\Exception $exception) {
            return [];
        }

        /** @var PathFilterIterator $iterator */
        $iterator = tap($resources->getIterator())
            ->rewind();


        return collect($iterator)
            ->map(function (SplFileInfo $file) use ($directory) {
                return $this->resolveFileToClass($directory, $file);
            })
            ->filter(function (string $class) {
                return is_subclass_of($class, Resource::class)
                    && ! (new \ReflectionClass($class))->isAbstract();
            })
            ->toArray();
    }

    /**
     * @param string      $directory
     * @param SplFileInfo $file
     *
     * @return string
     */
    private function resolveFileToClass(string $directory, SplFileInfo $file): string
    {
        return $this->namespace . str_replace(
            [$directory, '/', '.php'],
            ['', '\\', ''],
            $file->getPathname()
        );
    }
}

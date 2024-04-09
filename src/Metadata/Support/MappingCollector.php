<?php

namespace Articulate\Metadata\Support;

use Articulate\Metadata\Mapping;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

final class MappingCollector
{
    private string $path;

    private string $namespace;

    /**
     * @var array<int, class-string>
     */
    private array $classes;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private Filesystem $files;

    /**
     * @param string                            $path
     * @param string                            $namespace
     * @param array<int, class-string>          $classes
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(string $path, string $namespace, array $classes, Filesystem $files)
    {
        $this->path      = $path;
        $this->namespace = $namespace;
        $this->classes   = $classes;
        $this->files     = $files;
    }

    /**
     * @return \Illuminate\Support\Collection<class-string, \Articulate\Metadata\Mapping>
     */
    public function collect(): Collection
    {
        return $this->collectFromPath()
                    ->merge($this->collectClasses())
                    ->map(function (string $class) {
                        return app($class);
                    })
                    ->keyBy(function (Mapping $mapping) {
                        return $mapping->class();
                    });
    }

    /**
     * @return \Illuminate\Support\Collection<int, class-string<\Articulate\Metadata\Mapping>
     */
    private function collectFromPath(): Collection
    {
        return $this->filterMappings(
            Collection::make($this->files->glob($this->path . '/*.php'))
                      ->map(function (string $file) {
                          return $this->namespace . '\\' . basename($file, '.php');
                      })
        );
    }

    /**
     * @return \Illuminate\Support\Collection<int, class-string<\Articulate\Metadata\Mapping>>
     */
    private function collectClasses(): Collection
    {
        return $this->filterMappings(Collection::make($this->classes));
    }

    /**
     * @param \Illuminate\Support\Collection<int, string> $collection
     *
     * @return \Illuminate\Support\Collection<int, class-string<\Articulate\Metadata\Mapping>>
     */
    private function filterMappings(Collection $collection): Collection
    {
        return $collection->filter(function (string $class): bool {
            return class_exists($class) && is_subclass_of($class, Mapping::class);
        });
    }
}

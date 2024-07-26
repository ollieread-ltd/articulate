<?php
declare(strict_types=1);

namespace Articulate;

use Articulate\Managers\MetadataManager;
use Articulate\Managers\TypeManager;
use Articulate\Metadata\DiscoverMetadata;
use Illuminate\Contracts\Foundation\Application;

final class Articulate
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private Application $app;

    /**
     * The type manager
     *
     * @var \Articulate\Managers\TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var \Articulate\Managers\MetadataManager
     */
    private MetadataManager $metadataManager;

    public function __construct(Application $app)
    {
        $this->app             = $app;
        $this->typeManager     = new TypeManager();
        $this->metadataManager = new MetadataManager($this->typeManager);
    }

    /**
     * Get the type manager
     *
     * @return \Articulate\Managers\TypeManager
     */
    public function types(): TypeManager
    {
        return $this->typeManager;
    }

    /**
     * Get the metadata manager
     *
     * @return \Articulate\Managers\MetadataManager
     */
    public function metadata(): MetadataManager
    {
        return $this->metadataManager;
    }

    /**
     * Initialise Articulate
     *
     * @return void
     */
    public function init(): void
    {
        $this->initialiseTypes();
        $this->initialiseDefaultTypeMappings();
    }

    /**
     * Initialise the types
     *
     * @return void
     */
    private function initialiseTypes(): void
    {
        $types = $this->app['config']['articulate.types'] ?? [
            'property' => [],
            'column'  => [],
        ];

        // Register the property types
        foreach ($types['property'] as $propertyType) {
            $this->types()->register($propertyType);
        }

        // Register the column types
        foreach ($types['column'] as $columnType) {
            $this->types()->register($columnType);
        }
    }

    /**
     * Initialise the default mappings of types
     *
     * @return void
     */
    private function initialiseDefaultTypeMappings(): void
    {
        $defaults = $this->app['config']['articulate.types.defaults'] ?? [
            'property' => [],
            'column'  => [],
        ];

        // Register the default property mappings
        foreach ($defaults['property'] as $propertyType => $columnType) {
            $this->types()->mapPropertyDefaultColumnType($propertyType, $columnType);
        }

        // Register the default column mappings
        foreach ($defaults['column'] as $columnType => $propertyType) {
            $this->types()->mapColumnDefaultPropertyType($columnType, $propertyType);
        }
    }

    public function map(): void
    {
        // Fire we'll try and discover all the mappings to be discovered
        $mappings = $this->discoverMappings();

        // Let's map all the discovered mappings
        foreach ($mappings as $mapping) {
            $this->metadata()->map($mapping);
        }

        // Then we'll get any hardcoded classes
        $classMappings = $this->app['config']['articulate.mappings.classes'] ?? [];

        // And map those
        foreach ($classMappings as $classMapping) {
            $this->metadata()->map($classMapping);
        }
    }

    private function discoverMappings(): array
    {
        $locations = $this->app['config']['articulate.mappings.discovery'] ?? [];

        $mappings = [];

        foreach ($locations as $namespace => $path) {
            $mappings[] = DiscoverMetadata::within($path, $namespace);
        }

        return array_merge(...$mappings);
    }
}

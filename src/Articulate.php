<?php
declare(strict_types=1);

namespace Articulate;

use Articulate\Managers\MetadataManager;
use Articulate\Managers\TypeManager;
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
        $types = $this->app['config']['articulate.types'] ?? [];

        // Register the property types
        foreach ($types['properties'] as $propertyType) {
            $this->types()->register($propertyType);
        }

        // Register the column types
        foreach ($types['columns'] as $columnType) {
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
        $defaults = $this->app['config']['articulate.defaults'] ?? [];

        // Register the default property mappings
        foreach ($defaults['property'] as $propertyType => $columnType) {
            $this->types()->mapPropertyDefaultColumnType($propertyType, $columnType);
        }

        // Register the default column mappings
        foreach ($defaults['column'] as $columnType => $propertyType) {
            $this->types()->mapColumnDefaultPropertyType($columnType, $propertyType);
        }
    }
}

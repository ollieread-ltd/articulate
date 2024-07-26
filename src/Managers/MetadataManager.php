<?php

namespace Articulate\Managers;

use Articulate\Articulate;
use Articulate\Contracts\ComponentMetadata;
use Articulate\Contracts\EntityMetadata;
use Articulate\Contracts\Metadata;
use Articulate\Metadata\Mapping;
use Articulate\Metadata\MetadataBuilder;

/**
 *
 */
final class MetadataManager
{
    /**
     * @var \Articulate\Managers\TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var array<class-string<object>, \Articulate\Contracts\EntityMetadata<object>>
     */
    private array $entityMetadata = [];

    /**
     * @var array<class-string, \Articulate\Contracts\ComponentMetadata<object>>
     */
    private array $componentMetadata = [];

    public function __construct(TypeManager $typeManager)
    {
        $this->typeManager = $typeManager;
    }

    /**
     * @param string|\Articulate\Contracts\EntityMetadata<object>|\Articulate\Contracts\ComponentMetadata<object> $metadata
     *
     * @return self
     */
    public function register(string|EntityMetadata|ComponentMetadata $metadata): self
    {
        $metadata = is_string($metadata) ? new $metadata : $metadata;

        if ($metadata instanceof EntityMetadata) {
            $this->entityMetadata[$metadata->class()] = $metadata;
        } else if ($metadata instanceof ComponentMetadata) {
            $this->componentMetadata[$metadata->class()] = $metadata;
        }

        return $this;
    }

    /**
     * Get the metadata for an entity
     *
     * @template EClass of object
     *
     * @param class-string<EClass> $class
     *
     * @return \Articulate\Contracts\EntityMetadata<EClass>|null
     */
    public function entity(string $class): ?EntityMetadata
    {
        return $this->entityMetadata[$class] ?? null;
    }

    /**
     * Get the metadata for a component
     *
     * @template CClass of object
     *
     * @param class-string<CClass> $class
     *
     * @return \Articulate\Contracts\ComponentMetadata<CClass>|null
     */
    public function component(string $class): ?ComponentMetadata
    {
        return $this->componentMetadata[$class] ?? null;
    }

    /**
     * @template EClass of object
     *
     * @param class-string<\Articulate\Metadata\Mapping<EClass>> $class
     *
     * @return \Articulate\Contracts\Metadata<EClass>|null
     *
     * @throws \ReflectionException
     */
    public function map(string $class): ?Metadata
    {
        if (! class_exists($class) || ! is_subclass_of($class, Mapping::class)) {
            // TODO: Throw an exception
            return null;
        }

        // Create an instance of the mapping class
        $mapping = new $class;

        // Get a new metadata builder
        $builder = $this->getNewMetadataBuilder($mapping->class());

        // Right now there's no component functionality, so we'll default to an entity
        $builder->entity();

        // Then, we provide the mapping with the builder
        $mapping->map($builder);

        // Now we can build the metadata
        $metadata = $builder->build();

        // Register it, for future reference
        $this->register($metadata);

        // And then return it
        return $metadata;
    }

    /**
     * @template EClass of object
     *
     * @param class-string<EClass> $class
     *
     * @return \Articulate\Metadata\MetadataBuilder<EClass>
     */
    private function getNewMetadataBuilder(string $class): MetadataBuilder
    {
        return new MetadataBuilder(
            $this->typeManager,
            $class
        );
    }
}

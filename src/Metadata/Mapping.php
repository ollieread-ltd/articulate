<?php

namespace Articulate\Metadata;

/**
 * @template MetaClass of object
 */
abstract class Mapping
{
    /**
     * The class that is being mapped
     *
     * @return class-string<MetaClass>
     */
    abstract public function class(): string;

    /**
     * Map the metadata
     *
     * @param \Articulate\Metadata\MetadataBuilder<MetaClass> $metadata
     *
     * @return void
     */
    abstract public function map(MetadataBuilder $metadata): void;
}

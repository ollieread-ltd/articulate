<?php

namespace Articulate\Metadata;

abstract class Mapping
{
    /**
     * The class that is being mapped
     *
     * @return class-string
     */
    abstract public function class(): string;

    /**
     * Map the metadata
     *
     * @param \Articulate\Metadata\MetadataBuilder $metadata
     *
     * @return void
     */
    abstract public function map(MetadataBuilder $metadata): void;
}

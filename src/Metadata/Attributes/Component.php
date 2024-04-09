<?php

namespace Articulate\Metadata\Attributes;

use Articulate\Contracts\Enrichment;
use Articulate\Metadata\MetadataBuilder;
use Attribute;

/**
 * Component
 *
 * This attribute marks a class as a component for the purpose of metadata.
 *
 * @package Metadata
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Component implements Enrichment
{
    public string $table;

    public ?string $connection;

    public function __construct(string $table, ?string $connection = null)
    {
        $this->table      = $table;
        $this->connection = $connection;
    }

    /**
     * Enrich the metadata
     *
     * @template MetaClass of object
     *
     * @param \Articulate\Metadata\MetadataBuilder<MetaClass> $metadata
     *
     * @return void
     */
    public function enrich(MetadataBuilder $metadata): void
    {
        // Mark the class as a component
        $metadata->component();
    }
}

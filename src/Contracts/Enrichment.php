<?php

namespace Articulate\Contracts;

use Articulate\Metadata\MetadataBuilder;

/**
 * Enrichment Contract
 *
 * The enrichment contract is added to attributes that enrich class metadata
 * at a class level.
 */
interface Enrichment
{
    /**
     * Enrich the metadata
     *
     * @template MetaClass of object
     *
     * @param \Articulate\Metadata\MetadataBuilder<MetaClass> $metadata
     *
     * @return void
     */
    public function enrich(MetadataBuilder $metadata): void;
}

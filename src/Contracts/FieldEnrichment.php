<?php

namespace Articulate\Contracts;

use Articulate\Metadata\FieldBuilder;

/**
 * Field Enrichment Contract
 *
 * The field enrichment contract is added to attributes that enrich class
 * metadata at a field level.
 */
interface FieldEnrichment
{
    /**
     * Enrich the field metadata
     *
     * @param \Articulate\Metadata\FieldBuilder $field
     *
     * @return void
     */
    public function enrich(FieldBuilder $field): void;
}

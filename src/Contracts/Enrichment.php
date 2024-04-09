<?php

namespace Articulate\Contracts;

use Articulate\Metadata\MetadataBuilder;

interface Enrichment
{
    public function enrich(MetadataBuilder $metadata): void;
}

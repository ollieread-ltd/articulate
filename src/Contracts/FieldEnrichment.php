<?php

namespace Articulate\Contracts;

use Articulate\Metadata\FieldBuilder;

interface FieldEnrichment
{
    public function enrich(FieldBuilder $field): void;
}

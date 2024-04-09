<?php

namespace Articulate\Metadata\Attributes;

use Articulate\Contracts\FieldEnrichment;
use Articulate\Metadata\FieldBuilder;
use Articulate\Metadata\Support\TouchEvent;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Invisible implements FieldEnrichment
{
    /**
     * Enrich the field metadata
     *
     * @param \Articulate\Metadata\FieldBuilder $field
     *
     * @return void
     */
    public function enrich(FieldBuilder $field): void
    {
        $field->invisible();
    }
}

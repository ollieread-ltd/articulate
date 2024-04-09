<?php

namespace Articulate\Metadata\Attributes;

use Articulate\Contracts\FieldEnrichment;
use Articulate\Metadata\FieldBuilder;
use Articulate\Metadata\Support\TouchEvent;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Touchable implements FieldEnrichment
{
    /**
     * @var \Articulate\Metadata\Support\TouchEvent[]
     */
    private array $touchEvents;

    public function __construct(TouchEvent ...$touchEvents)
    {
        $this->touchEvents = $touchEvents;
    }

    /**
     * Enrich the field metadata
     *
     * @param \Articulate\Metadata\FieldBuilder $field
     *
     * @return void
     */
    public function enrich(FieldBuilder $field): void
    {
        $field->touchable(...$this->touchEvents);
    }
}

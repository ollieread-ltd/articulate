<?php

namespace Articulate\Metadata\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Column
{
    public string $name;

    public ?string $type;

    public function __construct(string $name, ?string $type = null)
    {
        $this->name = $name;
        $this->type = $type;
    }
}

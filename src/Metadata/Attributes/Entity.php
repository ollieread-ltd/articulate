<?php

namespace Articulate\Metadata\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Entity
{
    public string $table;

    public ?string $connection;

    public function __construct(string $table, ?string $connection = null)
    {
        $this->table      = $table;
        $this->connection = $connection;
    }
}

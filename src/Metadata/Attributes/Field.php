<?php

namespace Articulate\Metadata\Attributes;

use Articulate\Contracts\FieldEnrichment;
use Articulate\Metadata\FieldBuilder;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Field implements FieldEnrichment
{
    public ?string $columnName;

    public ?string $columnType;

    /**
     * @param string|null $columnName
     * @param string|null $columnType
     */
    public function __construct(?string $columnName = null, ?string $columnType = null)
    {
        $this->columnName = $columnName;
        $this->columnType = $columnType;
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
        // If a column name was provided...
        if ($this->columnName !== null) {
            // ...set the column name and type
            $field->column($this->columnName, $this->columnType);
        }
    }
}

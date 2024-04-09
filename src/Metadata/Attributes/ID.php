<?php

namespace Articulate\Metadata\Attributes;

use Articulate\Contracts\FieldEnrichment;
use Articulate\Metadata\FieldBuilder;
use Articulate\Metadata\Types\Properties\IntegerPropertyType;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class ID extends Field
{
    public function __construct(string $columnName = 'id', string $columnType = 'int')
    {
        parent::__construct($columnName, $columnType);
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
        // Perform the base field enrichment
        parent::enrich($field);

        // IDs are primary keys
        $field->primary();

        // Set the characteristics if it's an int field
        if ($this->columnType === 'int') {
            $field->big()->unsigned()->autoIncrementing();
        }
    }
}

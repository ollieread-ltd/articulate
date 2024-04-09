<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field;
use Articulate\Contracts\FieldType;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\Nullable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * Base Boolean Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\FieldType<mixed, bool>
 */
abstract class BooleanType implements FieldType
{
    public const NAME = 'boolean';

    /**
     * Get the name of the field type
     *
     * @return string
     */
    public function name(): string
    {
        return static::NAME;
    }

    /**
     * Cast a value to the appropriate type
     *
     * @param mixed                                    $value
     * @param \Articulate\Contracts\Field<mixed, bool> $field
     * @param \Articulate\Contracts\Metadata<object>   $metadata
     *
     * @return bool|null
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?bool
    {
        // If it's already the right type, return it
        if (is_bool($value)) {
            return $value;
        }

        // We'll return early if it's null. If the property isn't nullable, there
        // will be an error somewhere.
        if ($value === null) {
            // If the value is null and the field is nullable, we can return null
            if ($field->is(Nullable::class)) {
                return null;
            }

            // But if the field isn't nullable, we will return its default value
            // if it has one, and failing that, a default bool
            return $field->as(DefaultValue::class)?->value ?? false;
        }

        // We can assume it's just a bool, so we'll cast it
        return (bool)$value;
    }
}

<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field;
use Articulate\Contracts\FieldType;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Characteristics\AutoIncrementing;
use Articulate\Metadata\Characteristics\Big;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\Medium;
use Articulate\Metadata\Characteristics\Nullable;
use Articulate\Metadata\Characteristics\Small;
use Articulate\Metadata\Characteristics\Tiny;
use Articulate\Metadata\Characteristics\Unsigned;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * Base Integer Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\FieldType<mixed, int>
 */
abstract class IntegerType implements FieldType
{
    public const NAME = 'integer';

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
     * @param mixed                                   $value
     * @param \Articulate\Contracts\Field<mixed, int> $field
     * @param \Articulate\Contracts\Metadata<object>  $metadata
     *
     * @return int|null
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?int
    {
        // If it's already the right type, return it
        if (is_int($value)) {
            return $value;
        }

        if ($value === null) {
            // If the value is null and the field is nullable, we can return null
            if ($field->is(Nullable::class)) {
                return null;
            }

            // But if the field isn't nullable, we will return its default value
            // if it has one, and failing that, a default int
            return $field->as(DefaultValue::class)?->value ?? 0;
        }

        // We can assume it's just an int, so we'll cast it
        return (int)$value;
    }
}

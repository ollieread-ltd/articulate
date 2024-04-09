<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties;

use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\Nullable;
use Carbon\Carbon;

/**
 * Integer Property Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\PropertyType<mixed, float>
 */
final class FloatPropertyType implements PropertyType
{
    public const NAME = 'float';

    /**
     * Get the name of the field type
     *
     * @return string
     */
    public function name(): string
    {
        return self::NAME;
    }

    /**
     * Cast a value to the appropriate type
     *
     * @param mixed                                     $value
     * @param \Articulate\Contracts\Field<mixed, float> $field
     * @param \Articulate\Contracts\Metadata<object>    $metadata
     *
     * @return float|null
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?float
    {
        // If it's already the right type, return it
        if (is_float($value)) {
            return $value;
        }

        if ($value === null) {
            // If the value is null and the field is nullable, we can return null
            if ($field->is(Nullable::class)) {
                return null;
            }

            // But if the field isn't nullable, we will return its default value
            // if it has one, and failing that, a default float
            return $field->as(DefaultValue::class)?->value ?? 0.00;
        }

        // We can assume it's just a float, so we'll cast it
        return (float)$value;
    }
}

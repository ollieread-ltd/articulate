<?php
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties;

use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\Nullable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use JsonSerializable;

/**
 * Integer Property Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\PropertyType<mixed, array<mixed>>
 */
final class ArrayPropertyType implements PropertyType
{
    public const NAME = 'array';

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
     * @param mixed                                            $value
     * @param \Articulate\Contracts\Field<mixed, array<mixed>> $field
     * @param \Articulate\Contracts\Metadata<object>           $metadata
     *
     * @return array<mixed>|null
     *
     * @throws \JsonException
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?array
    {
        // If it's already the right type, return it
        if (is_array($value)) {
            return $value;
        }

        if ($value === null) {
            // If the value is null and the field is nullable, we can return null
            if ($field->is(Nullable::class)) {
                return null;
            }

            // But if the field isn't nullable, we will return its default value
            // if it has one, and failing that, an empty array
            return $field->as(DefaultValue::class)?->value ?? [];
        }

        // If it's a collection, return all the contents
        if ($value instanceof Collection) {
            return $value->all();
        }

        // If it's arrayable, make it an array
        if ($value instanceof Arrayable) {
            return $value->toArray();
        }

        // If it's JSON serialisable, roll the dice and hope it returns an array
        if ($value instanceof JsonSerializable) {
            return $value->jsonSerialize();
        }

        // If it's a string we'll assume it's JSON
        if (is_string($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        // If all else fails, type hint as an array
        return (array)$value;
    }
}

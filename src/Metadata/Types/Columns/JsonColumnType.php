<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Columns;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Metadata\Characteristics\Binary;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\Nullable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * Integer Property Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\ColumnType<mixed, string>
 */
final class JsonColumnType implements ColumnType
{
    public const NAME = 'json';

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
     * @param mixed                                      $value
     * @param \Articulate\Contracts\Field<mixed, string> $field
     * @param \Articulate\Contracts\Metadata<object>     $metadata
     *
     * @return string|null
     *
     * @throws \JsonException
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?string
    {
        // If it's already the right type, return it
        if (is_string($value)) {
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
            // if it has one, and failing that, a default string
            return $field->as(DefaultValue::class)?->value;
        }

        // We can assume it's just a string, so we'll cast it
        return json_encode($value, JSON_THROW_ON_ERROR);
    }

    /**
     * Define the column for the database
     *
     * @param \Illuminate\Database\Schema\Blueprint     $blueprint
     * @param \Articulate\Contracts\Field<mixed, mixed> $field
     * @param \Articulate\Contracts\Metadata<object>    $metadata
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function define(Blueprint $blueprint, Field $field, Metadata $metadata): ColumnDefinition
    {
        if ($field->is(Binary::class)) {
            return $blueprint->jsonb($field->column());
        }

        return $blueprint->json($field->column());
    }
}

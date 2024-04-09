<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Columns;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\EntityMetadata;
use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\Formatted;
use Articulate\Metadata\Characteristics\Nullable;
use DateTimeInterface;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * Integer Property Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\ColumnType<mixed, string>
 */
final class TimestampColumnType implements ColumnType
{
    public const NAME = 'timestamp';

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
            // if it has one, and failing that, a default timestamp
            return $field->as(DefaultValue::class)?->value;
        }

        // If it's a DateTime instance, we'll just format it
        if ($value instanceof DateTimeInterface) {
            // Use the format characteristic if present,
            // or use the default format provided by the grammar
            return $value->format($this->getDateFormat($field, $metadata));
        }

        // We can assume it's just a timestamp, so we'll cast it
        return (string)$value;
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
        return $blueprint->timestamp($field->column());
    }

    /**
     * Get the format of the timestamp
     *
     * @param \Articulate\Contracts\Field<mixed, mixed> $field
     * @param \Articulate\Contracts\Metadata<object>    $metadata
     *
     * @return string
     */
    private function getDateFormat(Field $field, Metadata $metadata): string
    {
        $format = $field->as(Formatted::class)?->format;

        if ($format !== null) {
            return $format;
        }

        if ($metadata instanceof EntityMetadata) {
            $connection = $metadata->connection();

            if ($connection instanceof Connection || method_exists($connection, 'getQueryGrammar')) {
                return $connection->getQueryGrammar()->getDateFormat();
            }
        }

        return 'Y-m-d H:i:s';
    }
}

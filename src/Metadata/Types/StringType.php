<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field;
use Articulate\Contracts\FieldType;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Characteristics\Length;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Stringable;

/**
 * @implements \Articulate\Contracts\FieldType<mixed, string>
 */
abstract class StringType implements FieldType
{
    public const NAME = 'string';

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
            return $value;
        }

        // If it's stringable, we can call the magic method
        if ($value instanceof Stringable) {
            return $value->__toString();
        }

        // If there's a method called toString, we can call that
        if (method_exists($value, 'toString')) {
            return $value->toString();
        }

        // Cast it to a string if all else fails
        return (string)$value;
    }
}

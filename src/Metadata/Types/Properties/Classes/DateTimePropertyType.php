<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties\Classes;

use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyClassType;
use Articulate\Metadata\Characteristics\Formatted;
use Articulate\Metadata\Characteristics\Immutable;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * DateTime Property Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\PropertyClassType<mixed, DateTimeInterface>
 */
class DateTimePropertyType implements PropertyClassType
{
    public const NAME = DateTimeInterface::class;

    /**
     * The class type this property is for
     *
     * @return class-string
     */
    public function class(): string
    {
        return self::NAME;
    }

    /**
     * Whether to match properties of the exact type
     *
     * @return bool
     */
    public function exactMatch(): bool
    {
        return false;
    }

    /**
     * Get the name of the field type
     *
     * @return string
     */
    public function name(): string
    {
        return $this->class();
    }

    /**
     * Cast a value to the appropriate type
     *
     * @param mixed                                                 $value
     * @param \Articulate\Contracts\Field<mixed, DateTimeInterface> $field
     * @param \Articulate\Contracts\Metadata<object>                $metadata
     *
     * @return DateTimeInterface|null
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?DateTimeInterface
    {
        // If it's already the right type, return it
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        // We'll return early if it's null. If the property isn't nullable, there
        // will be an error somewhere.
        if ($value === null) {
            return $value;
        }

        $class = $field->is(Immutable::class) ? DateTimeImmutable::class : DateTime::class;

        // If it's an int, it's a timestamp
        if (is_int($value)) {
            return $class::createFromFormat('U', $value);
        }

        if (is_string($value) && $field->is(Formatted::class)) {
            return $class::createFromFormat($field->as(Formatted::class)?->format, $value);
        }

        // We have no idea what the value is, so we'll take a punt
        return new $class($value);
    }
}

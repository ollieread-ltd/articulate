<?php
/** @noinspection ClassReImplementsParentInterfaceInspection */
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties;

use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Contracts\PropertyClassType;
use Carbon\Carbon;
use DateTimeInterface;

/**
 * Carbon Property Type
 *
 * @package Metadata
 *
 * @implements \Articulate\Contracts\PropertyClassType<mixed, Carbon>
 */
final class CarbonPropertyType extends DateTimePropertyType implements PropertyClassType
{
    public const NAME = Carbon::class;

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
     * Cast a value to the appropriate type
     *
     * @param mixed                                      $value
     * @param \Articulate\Contracts\Field<mixed, Carbon|DateTimeInterface> $field
     * @param \Articulate\Contracts\Metadata<object>     $metadata
     *
     * @return Carbon|null
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): ?Carbon
    {
        $value = parent::cast($value, $field, $metadata);

        if ($value instanceof DateTimeInterface) {
            return new Carbon($value);
        }

        return $value;
    }
}

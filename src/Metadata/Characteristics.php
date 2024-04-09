<?php

namespace Articulate\Metadata;

use Articulate\Contracts\FieldCharacteristic;
use Articulate\Metadata\Characteristics\AfterColumn;
use Articulate\Metadata\Characteristics\AutoIncrementing;
use Articulate\Metadata\Characteristics\Big;
use Articulate\Metadata\Characteristics\Binary;
use Articulate\Metadata\Characteristics\DefaultingToCurrentTimestamp;
use Articulate\Metadata\Characteristics\DefaultValue;
use Articulate\Metadata\Characteristics\First;
use Articulate\Metadata\Characteristics\Formatted;
use Articulate\Metadata\Characteristics\FulltextIndexed;
use Articulate\Metadata\Characteristics\Immutable;
use Articulate\Metadata\Characteristics\Indexed;
use Articulate\Metadata\Characteristics\Invisible;
use Articulate\Metadata\Characteristics\Length;
use Articulate\Metadata\Characteristics\Medium;
use Articulate\Metadata\Characteristics\NeverNull;
use Articulate\Metadata\Characteristics\Nullable;
use Articulate\Metadata\Characteristics\Precise;
use Articulate\Metadata\Characteristics\Small;
use Articulate\Metadata\Characteristics\SpatiallyIndexed;
use Articulate\Metadata\Characteristics\Tiny;
use Articulate\Metadata\Characteristics\Touchable;
use Articulate\Metadata\Characteristics\Unique;
use Articulate\Metadata\Characteristics\Unsigned;
use Articulate\Metadata\Characteristics\UsingCharset;
use Articulate\Metadata\Characteristics\UsingCollation;
use Articulate\Metadata\Characteristics\UsingCurrentTimestampOnUpdate;
use Articulate\Metadata\Support\TouchEvent;

final class Characteristics
{
    /**
     * Singleton characteristics
     *
     * @var array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic>
     */
    private static array $characteristics = [];

    /**
     * Characteristics that should be treated as singletons
     *
     * @var array<class-string<\Articulate\Contracts\FieldCharacteristic>>
     */
    private static array $singletons = [
        Big::class,
        Binary::class,
        DefaultingToCurrentTimestamp::class,
        First::class,
        Immutable::class,
        Invisible::class,
        Medium::class,
        NeverNull::class,
        Nullable::class,
        Small::class,
        Tiny::class,
        Unsigned::class,
        UsingCurrentTimestampOnUpdate::class,
    ];

    /**
     * Make or get a field characteristic
     *
     * @template CharType of \Articulate\Contracts\FieldCharacteristic
     *
     * @param class-string<CharType> $characteristic
     * @param mixed                  ...$arguments
     *
     * @return \Articulate\Contracts\FieldCharacteristic
     *
     * @psalm-return CharType
     * @phpstan-return CharType
     */
    public static function make(string $characteristic, mixed ...$arguments): FieldCharacteristic
    {
        // Is this characteristic a singleton?
        if (in_array($characteristic, self::$singletons, true)) {
            // It is a singleton, but do we already have an instance?
            if (! isset(self::$characteristics[$characteristic])) {
                // We don't, so let's create one and use the arguments, just in-case
                self::$characteristics[$characteristic] = new $characteristic(...$arguments);
            }

            // Return either the previously or newly created instance
            return self::$characteristics[$characteristic];
        }

        // Create a new instance with the provided details
        return new $characteristic(...$arguments);
    }

    public static function tiny(): Tiny
    {
        return self::make(Tiny::class);
    }

    public static function small(): Small
    {
        return self::make(Small::class);
    }

    public static function medium(): Medium
    {
        return self::make(Medium::class);
    }

    public static function big(): Big
    {
        return self::make(Big::class);
    }

    public static function unsigned(): Unsigned
    {
        return self::make(Unsigned::class);
    }

    public static function autoIncrementing(?int $startingFrom = null): AutoIncrementing
    {
        return self::make(AutoIncrementing::class, $startingFrom);
    }

    public static function binary(): Binary
    {
        return self::make(Binary::class);
    }

    /**
     * @template ValType of mixed
     *
     * @param ValType $value
     *
     * @return \Articulate\Metadata\Characteristics\DefaultValue<ValType>
     */
    public static function default(mixed $value): DefaultValue
    {
        return self::make(DefaultValue::class, $value);
    }

    public static function useCurrent(): DefaultingToCurrentTimestamp
    {
        return self::make(DefaultingToCurrentTimestamp::class);
    }

    public static function useCurrentOnupdate(): UsingCurrentTimestampOnUpdate
    {
        return self::make(UsingCurrentTimestampOnUpdate::class);
    }

    public static function formatted(string $format): Formatted
    {
        return self::make(Formatted::class, $format);
    }

    public static function immutable(): Immutable
    {
        return self::make(Immutable::class);
    }

    public static function invisible(): Invisible
    {
        return self::make(Invisible::class);
    }

    public static function length(int $length): Length
    {
        return self::make(Length::class, $length);
    }

    public static function notNull(): NeverNull
    {
        return self::make(NeverNull::class);
    }

    public static function nullable(): Nullable
    {
        return self::make(Nullable::class);
    }

    public static function precise(int $length): Precise
    {
        return self::make(Precise::class, $length);
    }

    public static function touchable(TouchEvent ...$events): Touchable
    {
        return self::make(Touchable::class, ...$events);
    }

    public static function unique(bool|string|null $name = null): Unique
    {
        return self::make(Unique::class, $name);
    }

    public static function indexed(bool|string|null $name = null): Indexed
    {
        return self::make(Indexed::class, $name);
    }

    public static function fulltextIndexed(bool|string|null $name = null): FulltextIndexed
    {
        return self::make(FulltextIndexed::class, $name);
    }

    public static function spatiallyIndexed(bool|string|null $name = null): SpatiallyIndexed
    {
        return self::make(SpatiallyIndexed::class, $name);
    }

    public static function useCharset(string $charset): UsingCharset
    {
        return self::make(UsingCharset::class, $charset);
    }

    public static function useCollation(string $collation): UsingCollation
    {
        return self::make(UsingCollation::class, $collation);
    }

    public static function after(string $column): AfterColumn
    {
        return self::make(AfterColumn::class, $column);
    }

    public static function first(): First
    {
        return self::make(First::class);
    }
}

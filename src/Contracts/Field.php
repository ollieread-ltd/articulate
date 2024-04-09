<?php

namespace Articulate\Contracts;

/**
 * Field
 *
 * @package Metadata
 *
 * @template InType of mixed
 * @template OutType of mixed
 */
interface Field
{
    /**
     * Get the name of the property that stores the field
     *
     * @return string
     */
    public function property(): string;

    /**
     * Get the type representing the property
     *
     * @return \Articulate\Contracts\PropertyType<OutType, InType>
     */
    public function propertyType(): PropertyType;

    /**
     * Get the name of the database column that stores the field
     *
     * @return string
     */
    public function column(): string;

    /**
     * Get the type representing the column
     *
     * @return \Articulate\Contracts\ColumnType<InType, OutType>
     */
    public function columnType(): ColumnType;

    /**
     * Get the fields' characteristics
     *
     * @return array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic>
     */
    public function characteristics(): array;

    /**
     * Check if a field characteristic is present
     *
     * @param class-string<\Articulate\Contracts\FieldCharacteristic> $characteristic
     *
     * @return bool
     */
    public function is(string $characteristic): bool;

    /**
     * Get a field characteristic
     *
     * @template CharType of \Articulate\Contracts\FieldCharacteristic
     *
     * @param class-string<\Articulate\Contracts\FieldCharacteristic> $characteristic
     *
     * @return \Articulate\Contracts\FieldCharacteristic|null
     *
     * @psalm-param class-string<CharType>                            $characteristic
     * @phpstan-param class-string<CharType>                          $characteristic
     *
     * @psalm-return CharType|null
     * @psalm-return CharType|null
     */
    public function as(string $characteristic): ?FieldCharacteristic;
}

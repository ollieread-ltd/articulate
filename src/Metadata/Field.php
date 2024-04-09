<?php

namespace Articulate\Metadata;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field as FieldContract;
use Articulate\Contracts\FieldCharacteristic;
use Articulate\Contracts\PropertyType;

/**
 * Field
 *
 * @package Metadata
 *
 * @template InType of mixed
 * @template OutType of mixed
 *
 * @implements \Articulate\Contracts\Field<InType, OutType>
 */
final class Field implements FieldContract
{
    /**
     * @var string
     */
    private string $propertyName;

    /**
     * @var \Articulate\Contracts\PropertyType<OutType, InType>
     */
    private PropertyType $propertyType;

    /**
     * @var string
     */
    private string $columnName;

    /**
     * @var \Articulate\Contracts\ColumnType<InType, OutType>
     */
    private ColumnType $columnType;

    /**
     * @var array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic>
     */
    private array $characteristics;

    /**
     * @param string                                                                                                    $propertyName
     * @param \Articulate\Contracts\PropertyType<OutType, InType>                                                       $propertyType
     * @param string                                                                                                    $columnName
     * @param \Articulate\Contracts\ColumnType<InType, OutType>                                                         $columnType
     * @param array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic> $characteristics
     */
    public function __construct(
        string       $propertyName,
        PropertyType $propertyType,
        string       $columnName,
        ColumnType   $columnType,
        array        $characteristics = []
    )
    {
        $this->propertyName    = $propertyName;
        $this->propertyType    = $propertyType;
        $this->columnName      = $columnName;
        $this->columnType      = $columnType;
        $this->characteristics = $characteristics;
    }

    /**
     * Get the name of the property that stores the field
     *
     * @return string
     */
    public function property(): string
    {
        return $this->propertyName;
    }

    /**
     * Get the type representing the property
     *
     * @return \Articulate\Contracts\PropertyType<OutType, InType>
     */
    public function propertyType(): PropertyType
    {
        return $this->propertyType;
    }

    /**
     * Get the name of the database column that stores the field
     *
     * @return string
     */
    public function column(): string
    {
        return $this->columnName;
    }

    /**
     * Get the type representing the column
     *
     * @return \Articulate\Contracts\ColumnType<InType, OutType>
     */
    public function columnType(): ColumnType
    {
        return $this->columnType;
    }

    /**
     * Get the fields' characteristics
     *
     * @return array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic>
     */
    public function characteristics(): array
    {
        return $this->characteristics;
    }

    /**
     * Check if a field characteristic is present
     *
     * @param class-string<\Articulate\Contracts\FieldCharacteristic> $characteristic
     *
     * @return bool
     */
    public function is(string $characteristic): bool
    {
        return isset($this->characteristics[$characteristic]);
    }

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
    public function as(string $characteristic): ?FieldCharacteristic
    {
        return $this->characteristics[$characteristic] ?? null;
    }
}

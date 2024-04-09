<?php

namespace Articulate\Contracts;

/**
 * Metadata
 *
 * Metadata contains the mapping of class properties to database columns.
 *
 * @package Metadata
 *
 * @template MetaClass of object
 */
interface Metadata
{
    /**
     * Get the class the metadata represents
     *
     * @return class-string<MetaClass>
     */
    public function class(): string;

    /**
     * Get the entity fields
     *
     * @return array<string, \Articulate\Contracts\Field<mixed, mixed>>
     */
    public function fields(): array;

    /**
     * Check if a field exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Get a field by its name
     *
     * @param string $name
     *
     * @return \Articulate\Contracts\Field<mixed, mixed>|null
     */
    public function field(string $name): ?Field;

    /**
     * Get a field by its column name
     *
     * @param string $name
     *
     * @return \Articulate\Contracts\Field<mixed, mixed>|null
     */
    public function column(string $name): ?Field;

    /**
     * Get all fields that have a characteristic
     *
     * @param class-string<\Articulate\Contracts\FieldCharacteristic> $characteristic
     *
     * @return array<string, \Articulate\Contracts\Field<mixed, mixed>>
     */
    public function fieldsBy(string $characteristic): array;
}

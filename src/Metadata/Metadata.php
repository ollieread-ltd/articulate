<?php
declare(strict_types=1);

namespace Articulate\Metadata;

use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata as MetadataContract;
use Illuminate\Support\Collection;

/**
 * Metadata
 *
 * An abstract metadata class that provides the base functionality of the
 * {@see \Articulate\Metadata\Metadata} contract.
 *
 * @package Metadata
 *
 * @template EClass of object
 *
 * @implements MetadataContract<EClass>
 */
abstract class Metadata implements MetadataContract
{
    /**
     * The entity class
     *
     * @var class-string<EClass>
     */
    private string $entityClass;

    /**
     * The entity fields
     *
     * @var \Illuminate\Support\Collection<string, \Articulate\Contracts\Field<mixed, mixed>>
     */
    private Collection $fields;

    /**
     * A mapping of column names to field names
     *
     * @var array<string, string>
     */
    private array $columnToFieldMapping = [];

    /**
     * Create a new instance of the metadata
     *
     * @param class-string<EClass>           $entityClass
     * @param \Illuminate\Support\Collection<string, \Articulate\Contracts\Field<mixed, mixed>> $fields
     */
    public function __construct(
        string     $entityClass,
        Collection $fields
    )
    {
        $this->entityClass = $entityClass;
        $this->fields      = $fields;

        $this->mapColumnsToFields();
    }

    /**
     * Map the column names to their field names
     *
     * @return void
     */
    private function mapColumnsToFields(): void
    {
        $this->fields->each(function (Field $field) {
            $this->columnToFieldMapping[$field->column()] = $field->property();
        });
    }

    /**
     * Get the class the metadata represents
     *
     * @return class-string<EClass>
     */
    public function class(): string
    {
        return $this->entityClass;
    }

    /**
     * Get the entity fields
     *
     * @return array<string, \Articulate\Contracts\Field<mixed, mixed>>
     */
    public function fields(): array
    {
        return $this->fields->all();
    }

    /**
     * Check if a field exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->fields->has($name);
    }

    /**
     * Get a field by its name
     *
     * @param string $name
     *
     * @return \Articulate\Contracts\Field<mixed, mixed>|null
     */
    public function field(string $name): ?Field
    {
        return $this->fields->get($name);
    }

    /**
     * Get a field by its column name
     *
     * @param string $name
     *
     * @return \Articulate\Contracts\Field<mixed, mixed>|null
     */
    public function column(string $name): ?Field
    {
        $fieldName = $this->columnToFieldMapping[$name] ?? null;

        if ($fieldName === null) {
            return null;
        }

        return $this->field($fieldName);
    }

    /**
     * Get all fields that have a characteristic
     *
     * @param class-string<\Articulate\Contracts\FieldCharacteristic> $characteristic
     *
     * @return array<string, \Articulate\Contracts\Field<mixed, mixed>>
     */
    public function fieldsBy(string $characteristic): array
    {
        return $this->fields->filter(function (Field $field) use ($characteristic) {
            return $field->is($characteristic);
        })->all();
    }
}

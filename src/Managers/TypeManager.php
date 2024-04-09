<?php
declare(strict_types=1);

namespace Articulate\Managers;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\PropertyClassType;
use Articulate\Contracts\PropertyType;
use Illuminate\Database\Schema\ColumnDefinition;
use InvalidArgumentException;
use ReflectionNamedType;

/**
 * Type Manager
 */
final class TypeManager
{
    /**
     * Property types mapped by name
     *
     * @var array<string, \Articulate\Contracts\PropertyType<mixed, mixed>>
     */
    private array $propertyTypes = [];

    /**
     * Property types that match exact class types
     *
     * @var array<class-string, string>
     */
    private array $propertyClassMatch = [];

    /**
     * Property types that match instance of class types
     *
     * @var array<class-string, string>
     */
    private array $propertyClassInstanceMatch = [];

    /**
     * Column types mapped by name
     *
     * @var array<string, \Articulate\Contracts\ColumnType<mixed, mixed>>
     */
    private array $columnTypes = [];

    /**
     * Property type names mapped to default column type names
     *
     * @var array<string, string>
     */
    private array $propertyColumnTypes = [];

    /**
     * Column type names mapped to default property type names
     *
     * @var array<string, string>
     */
    private array $columnPropertyTypes = [];

    /**
     * Register a column or property type
     *
     * @param string|\Articulate\Contracts\PropertyType<mixed, mixed>|\Articulate\Contracts\ColumnType<mixed, mixed> $type
     *
     * @return self
     */
    public function register(string|PropertyType|ColumnType $type): self
    {
        $type = is_string($type) ? new $type : $type;

        if ($type instanceof PropertyType) {
            return $this->registerPropertyType($type);
        }

        if ($type instanceof ColumnType) {
            $this->columnTypes[$type->name()] = $type;

            return $this;
        }

        throw new InvalidArgumentException('Provided class is neither a column nor a property type');
    }

    /**
     * Register a property type
     *
     * @param \Articulate\Contracts\PropertyType<mixed, mixed> $type
     *
     * @return self
     */
    public function registerPropertyType(PropertyType $type): self
    {
        $this->propertyTypes[$type->name()] = $type;

        if ($type instanceof PropertyClassType) {
            if ($type->exactMatch()) {
                $this->propertyClassMatch[$type->class()] = $type->name();
            } else {
                $this->propertyClassInstanceMatch[$type->class()] = $type->name();
            }
        }

        return $this;
    }

    /**
     * Map a property type to a default column type
     *
     * @param string $propertyType
     * @param string $columnType
     * @param bool   $reverse
     *
     * @return self
     */
    public function mapPropertyDefaultColumnType(string $propertyType, string $columnType, bool $reverse = true): self
    {
        $this->propertyColumnTypes[$propertyType] = $columnType;

        if ($reverse) {
            $this->mapColumnDefaultPropertyType($columnType, $propertyType, false);
        }

        return $this;
    }

    /**
     * Map a column type to a default property type
     *
     * @param string $propertyType
     * @param string $columnType
     * @param bool   $reverse
     *
     * @return self
     */
    public function mapColumnDefaultPropertyType(string $columnType, string $propertyType, bool $reverse = true): self
    {
        $this->columnPropertyTypes[$columnType] = $propertyType;

        if ($reverse) {
            $this->mapPropertyDefaultColumnType($propertyType, $columnType, false);
        }

        return $this;
    }

    /**
     * Get a property type by name
     *
     * @param string $name
     *
     * @return \Articulate\Contracts\PropertyType<mixed, mixed>|null
     */
    public function property(string $name): ?PropertyType
    {
        return $this->propertyTypes[$name] ?? null;
    }

    /**
     * Get a property type by a reflection type
     *
     * @param \ReflectionNamedType $type
     *
     * @return \Articulate\Contracts\PropertyType<mixed, mixed>|null
     */
    public function propertyByReflection(ReflectionNamedType $type): ?PropertyType
    {
        if ($type->isBuiltin()) {
            return $this->property($type->getName());
        }

        return $this->propertyByClass($type->getName());
    }

    /**
     * Get a property type by a class name
     *
     * @param class-string $class
     *
     * @return \Articulate\Contracts\PropertyType<mixed, mixed>|null
     */
    public function propertyByClass(string $class): ?PropertyType
    {
        if (isset($this->propertyClassMatch[$class])) {
            return $this->property($this->propertyClassMatch[$class]);
        }

        foreach ($this->propertyClassInstanceMatch as $classMatch => $property) {
            if (is_subclass_of($class, $classMatch)) {
                return $this->property($property);
            }
        }

        return null;
    }

    /**
     * Get a property type by its column type
     *
     * @param string $column
     *
     * @return \Articulate\Contracts\PropertyType<mixed, mixed>|null
     */
    public function propertyByColumn(string $column): ?PropertyType
    {
        $propertyName = $this->columnPropertyTypes[$column] ?? null;

        if ($propertyName === null) {
            return null;
        }

        return $this->property($propertyName);
    }

    /**
     * Get a class type by name
     *
     * @param string $name
     *
     * @return \Articulate\Contracts\ColumnType<mixed, mixed>|null
     */
    public function column(string $name): ?ColumnType
    {
        return $this->columnTypes[$name] ?? null;
    }

    /**
     * Get a column type by the migration definition
     *
     * @param \Illuminate\Database\Schema\ColumnDefinition $definition
     *
     * @return \Articulate\Contracts\ColumnType<mixed, mixed>|null
     */
    public function columnByDefinition(ColumnDefinition $definition): ?ColumnType
    {
        return $this->column($definition['type']);
    }

    /**
     * Get a column type by its property type
     *
     * @param string $property
     *
     * @return \Articulate\Contracts\ColumnType<mixed, mixed>|null
     */
    public function columnByProperty(string $property): ?ColumnType
    {
        $columnName = $this->propertyColumnTypes[$property] ?? null;

        if ($columnName === null) {
            return null;
        }

        return $this->column($columnName);
    }
}

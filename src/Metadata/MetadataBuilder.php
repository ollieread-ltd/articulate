<?php

namespace Articulate\Metadata;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\ComponentMetadata as ComponentMetadataContract;
use Articulate\Contracts\Enrichment;
use Articulate\Contracts\EntityMetadata as EntityMetadataContract;
use Articulate\Contracts\Field as FieldContract;
use Articulate\Contracts\FieldEnrichment;
use Articulate\Contracts\PropertyType;
use Articulate\Managers\TypeManager;
use Articulate\Metadata\Attributes\Field as FieldAttribute;
use Articulate\Metadata\Types\Columns\IntegerColumnType;
use Articulate\Metadata\Types\Properties\ArrayPropertyType;
use Articulate\Metadata\Types\Properties\BooleanPropertyType;
use Articulate\Metadata\Types\Properties\Classes\CarbonPropertyType;
use Articulate\Metadata\Types\Properties\Classes\DateTimePropertyType;
use Articulate\Metadata\Types\Properties\FloatPropertyType;
use Articulate\Metadata\Types\Properties\IntegerPropertyType;
use Articulate\Metadata\Types\Properties\StringPropertyType;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

/**
 * @template MetaClass of object
 */
final class MetadataBuilder
{
    /**
     * @var \Articulate\Managers\TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var class-string<MetaClass>
     */
    private string $class;

    /**
     * @var bool
     */
    private bool $entity = true;

    /**
     * @var string
     */
    private string $table;

    /**
     * @var string|null
     */
    private ?string $connection = null;

    /**
     * @var array<string, \Articulate\Metadata\FieldBuilder>
     */
    private array $fields = [];

    /**
     * @var \ReflectionClass<MetaClass>
     */
    private ReflectionClass $classReflection;

    /**
     * @var bool
     */
    private bool $enrich = true;

    /**
     * @var bool
     */
    private bool $enrichment = false;

    /**
     * Create a new instance of the metadata builder
     *
     * @param class-string<MetaClass> $class
     */
    public function __construct(TypeManager $typeManager, string $class)
    {
        $this->typeManager = $typeManager;
        $this->class       = $class;
    }

    /**
     * Mark the metadata as mapping an entity
     *
     * @return static
     */
    public function entity(): self
    {
        $this->entity = true;

        return $this;
    }

    /**
     * Make the metadata as mapping a component
     *
     * @return static
     */
    public function component(): self
    {
        $this->entity = false;

        return $this;
    }

    /**
     * Set the table the entity maps to
     *
     * @param string $table
     *
     * @return static
     */
    public function table(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Set the connection the entity uses
     *
     * @param string $connection
     *
     * @return static
     */
    public function connection(string $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Add a field
     *
     * @param string                                           $name
     * @param string|null                                      $propertyType
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function field(string $name, string $propertyType = null, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        $builder = $this->fields[$name] = new FieldBuilder($name);

        if ($propertyType !== null) {
            $builder->type($propertyType);
        }

        $builder->column($column, $columnType);
        $builder->characteristics(...$characteristics);

        return $builder;
    }

    /**
     * Add an id
     *
     * @param string $name
     * @param string $column
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function id(string $name = 'id', string $column = 'id'): FieldBuilder
    {
        return $this->field(
            $name,
            IntegerPropertyType::NAME,
            $column,
            IntegerColumnType::NAME,
            [
                Characteristics::big(),
                Characteristics::unsigned(),
                Characteristics::autoIncrementing(),
            ]
        );
    }

    /**
     * Add an array field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function array(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, ArrayPropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Add a boolean field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function bool(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, BooleanPropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Add a carbon datetime field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function carbon(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, CarbonPropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Add a class field
     *
     * @param string                                           $name
     * @param class-string                                     $class
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function class(string $name, string $class, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, $class, $column, $columnType, $characteristics);
    }

    /**
     * Add a datetime field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function dateTime(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, DateTimePropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Add an integer field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function integer(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, IntegerPropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Add a tiny integer field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function tinyInteger(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        $characteristics[] = Characteristics::tiny();

        return $this->integer($name, $column, $columnType, $characteristics);
    }

    /**
     * Add a small integer field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function smallInteger(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        $characteristics[] = Characteristics::small();

        return $this->integer($name, $column, $columnType, $characteristics);
    }

    /**
     * Add a medium integer field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function mediumInteger(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        $characteristics[] = Characteristics::medium();

        return $this->integer($name, $column, $columnType, $characteristics);
    }

    /**
     * Add a big integer field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function bigInteger(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        $characteristics[] = Characteristics::big();

        return $this->integer($name, $column, $columnType, $characteristics);
    }

    /**
     * Add a float field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function float(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, FloatPropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Add a string field
     *
     * @param string                                           $name
     * @param string|null                                      $column
     * @param string|null                                      $columnType
     * @param array<\Articulate\Contracts\FieldCharacteristic> $characteristics
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    public function string(string $name, string $column = null, string $columnType = null, array $characteristics = []): FieldBuilder
    {
        return $this->field($name, StringPropertyType::NAME, $column, $columnType, $characteristics);
    }

    /**
     * Do not perform enrichment through attributes
     *
     * @return static
     */
    public function doNotEnrich(): self
    {
        $this->enrich = false;

        return $this;
    }

    /**
     * Do perform enrichment through attributes
     *
     * @return static
     */
    public function doEnrich(): self
    {
        $this->enrich = true;

        return $this;
    }

    /**
     * Build the metadata
     *
     * @return \Articulate\Contracts\EntityMetadata<MetaClass>|\Articulate\Contracts\ComponentMetadata<MetaClass>
     *
     * @throws \ReflectionException
     */
    public function build(): EntityMetadataContract|ComponentMetadataContract
    {
        $this->enrich();

        if ($this->entity) {
            return $this->buildEntityMetadata();
        }

        return $this->buildComponentMetadata();
    }

    /**
     * Enrich the metadata using attributes
     *
     * @return static
     *
     * @throws \ReflectionException
     */
    public function enrich(): self
    {
        if ($this->shouldEnrich()) {
            $this->enrichClass();
            $this->enrichFields();

            $this->enrichment = true;
        }

        return $this;
    }

    /**
     * Should enrichment be performed
     *
     * @return bool
     */
    private function shouldEnrich(): bool
    {
        return $this->enrich
               && $this->enrichment === false
               && config('articulate.enrichment', true);
    }

    /**
     * Enrich the metadata based on the class attributes
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    private function enrichClass(): void
    {
        Collection::make(
        // Get all enrichment attributes
            $this->getClassReflection()->getAttributes(Enrichment::class, ReflectionAttribute::IS_INSTANCEOF)
        )->mapWithkeys(function (ReflectionAttribute $attribute) {
            // Turn them into instances and key them by their class
            return [$attribute->getName() => $attribute->newInstance()];
        })->each(function (Enrichment $attribute) {
            // Enrich
            $attribute->enrich($this);
        });
    }

    /**
     * Enrich the metadata based on the fields
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    private function enrichFields(): void
    {
        $this->collectFieldProperties()->each($this->enrichField(...));
    }

    /**
     * Collect all the properties that are considered fields
     *
     * @return \Illuminate\Support\Collection<string, \ReflectionProperty>
     *
     * @throws \ReflectionException
     */
    private function collectFieldProperties(): Collection
    {
        return Collection::make(
            $this->getClassReflection()->getProperties()
        )->keyBy(function (ReflectionProperty $property) {
            // Key them by their name
            return $property->getName();
        })->filter(function (ReflectionProperty $property) {
            // Filter out any that aren't fields
            return ! isset($this->fields[$property->getName()])
                   || empty($property->getAttributes(FieldAttribute::class));
        });
    }

    /**
     * Enrich the metadata for a single field
     *
     * @param \ReflectionProperty $property
     *
     * @return void
     */
    private function enrichField(ReflectionProperty $property): void
    {
        // Get the existing field or create one
        $field = $this->fields[$property->getName()] ?? $this->fieldForProperty($property);

        Collection::make(
            $property->getAttributes(FieldEnrichment::class, ReflectionAttribute::IS_INSTANCEOF)
        )->mapWithKeys(function (ReflectionAttribute $attribute) {
            // Turn them into instances and key them by their class
            return [$attribute->getName() => $attribute->newInstance()];
        })->each(function (FieldEnrichment $attribute) use ($field) {
            // Enrich
            $attribute->enrich($field);
        });
    }

    /**
     * Create a new field for a reflected property
     *
     * @param \ReflectionProperty $property
     *
     * @return \Articulate\Metadata\FieldBuilder
     */
    private function fieldForProperty(ReflectionProperty $property): FieldBuilder
    {
        return $this->field(
            $property->getName(),
            $this->getFieldPropertyTypeUsingReflection($property)->name()
        );
    }

    /**
     * Build the entity metadata
     *
     * @return \Articulate\Contracts\EntityMetadata<MetaClass>
     *
     * @throws \ReflectionException
     */
    private function buildEntityMetadata(): EntityMetadataContract
    {
        return new EntityMetadata(
            $this->class,
            $this->buildFields(),
            $this->getTableName(),
            $this->getConnectionName()
        );
    }

    /**
     * Build the component metadata
     *
     * @return \Articulate\Contracts\ComponentMetadata<MetaClass>
     *
     * @throws \ReflectionException
     */
    private function buildComponentMetadata(): ComponentMetadataContract
    {
        return new ComponentMetadata(
            $this->class,
            $this->buildFields()
        );
    }

    /**
     * Get a reflection instance of the class we're mapping
     *
     * @return \ReflectionClass<MetaClass>
     *
     * @throws \ReflectionException
     */
    private function getClassReflection(): ReflectionClass
    {
        if (! isset($this->classReflection)) {
            $this->classReflection = new ReflectionClass($this->class);
        }

        return $this->classReflection;
    }

    /**
     * Get a reflection instance for a property we're mapping
     *
     * @param string $propertyName
     *
     * @return \ReflectionProperty
     * @throws \ReflectionException
     */
    private function getPropertyReflection(string $propertyName): ReflectionProperty
    {
        $reflection = $this->getClassReflection();

        if (! $reflection->hasProperty($propertyName)) {
            throw new RuntimeException(sprintf('The property %s doesn\'t exist in class %s', $propertyName, $this->class));
        }

        return $reflection->getProperty($propertyName);
    }

    /**
     * Get the table name for the entity
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    private function getTableName(): string
    {
        return $this->table ?? $this->getTableNameFromClass();
    }

    /**
     * Generate the table name based on the class name
     *
     * @return string
     */
    private function getTableNameFromClass(): string
    {
        return Str::snake(Str::pluralStudly(class_basename($this->class)));
    }

    /**
     * Get the connection name for the entity
     *
     * @return string|null xz
     *
     * @throws \ReflectionException
     */
    private function getConnectionName(): ?string
    {
        return $this->connection ?? null;
    }

    /**
     * Build the collection of fields for mapping
     *
     * @return \Illuminate\Support\Collection<string, \Articulate\Contracts\Field>
     *
     * @throws \ReflectionException
     */
    private function buildFields(): Collection
    {
        $fields = new Collection();

        foreach ($this->fields as $name => $field) {
            $fields->put($name, $this->buildField($field));
        }

        return $fields;
    }

    /**
     * Build an individually mapped field
     *
     * @param \Articulate\Metadata\FieldBuilder $field
     *
     * @return \Articulate\Contracts\Field<mixed, mixed>
     *
     * @throws \ReflectionException
     */
    private function buildField(FieldBuilder $field): FieldContract
    {
        $fieldArray = $field->toArray();
        extract($fieldArray);

        $propertyType = $this->getFieldPropertyType($propertyType, $propertyName);

        return new Field(
            $propertyName,
            $propertyType,
            $this->getFieldColumnName($columnName, $propertyName),
            $this->getFieldColumnType($columnType, $propertyName, $propertyType),
            $characteristics
        );
    }

    /**
     * Get the type for a class property
     *
     * @param string|null $propertyType
     * @param string      $propertyName
     *
     * @return \Articulate\Contracts\PropertyType<mixed, mixed>
     *
     * @throws \ReflectionException
     */
    private function getFieldPropertyType(?string $propertyType, string $propertyName): PropertyType
    {
        if ($propertyType !== null) {
            return $this->typeManager->property($propertyType);
        }

        return $this->getFieldPropertyTypeUsingReflection($this->getPropertyReflection($propertyName));
    }

    /**
     * Get the field type using reflection
     *
     * @param \ReflectionProperty $reflection
     *
     * @return \Articulate\Contracts\PropertyType<mixed, mixed>
     */
    private function getFieldPropertyTypeUsingReflection(ReflectionProperty $reflection): PropertyType
    {
        $type = $reflection->getType();

        if (! ($type instanceof ReflectionNamedType)) {
            throw new RuntimeException(sprintf('The property %s in class %s has an invalid type for mapping', $reflection->name, $this->class));
        }

        return $this->typeManager->propertyByReflection($type);
    }

    /**
     * Get the mapped column name for a field
     *
     * @param string|null $columnName
     * @param string      $propertyName
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    private function getFieldColumnName(?string $columnName, string $propertyName): string
    {
        return $columnName
               ?? $this->getFieldColumnNameFromPropertyName($propertyName);
    }

    /**
     * Generate a mapped column name from the field property name
     *
     * @param string $propertyName
     *
     * @return string
     */
    private function getFieldColumnNameFromPropertyName(string $propertyName): string
    {
        return Str::snake($propertyName);
    }

    /**
     * Get the column type for a field
     *
     * @param string|null                                      $columnType
     * @param string                                           $propertyName
     * @param \Articulate\Contracts\PropertyType<mixed, mixed> $propertyType
     *
     * @return \Articulate\Contracts\ColumnType<mixed, mixed>
     *
     * @throws \ReflectionException
     */
    private function getFieldColumnType(?string $columnType, string $propertyName, PropertyType $propertyType): ColumnType
    {
        if ($columnType !== null) {
            return $this->typeManager->column($columnType);
        }

        $type = $this->typeManager->columnByProperty($propertyType->name());

        if ($type === null) {
            throw new RuntimeException(sprintf('Property %s in class %s has no valid column mapping', $propertyName, $this->class));
        }

        return $type;
    }
}

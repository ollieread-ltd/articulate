<?php

namespace Articulate\Metadata;

use Articulate\Concerns\AddsCharacteristics;
use Articulate\Contracts\FieldCharacteristic;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements \Illuminate\Contracts\Support\Arrayable<string, mixed>
 */
final class FieldBuilder implements Arrayable
{
    use AddsCharacteristics;

    /**
     * @var string
     */
    private string $propertyName;

    /**
     * @var string|null
     */
    private ?string $propertyType = null;

    /**
     * @var string|null
     */
    private ?string $columnName = null;

    /**
     * @var string|null
     */
    private ?string $columnType = null;

    /**
     * @var array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic>
     */
    private array $characteristics = [];

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->propertyName = $name;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function type(string $type): self
    {
        $this->propertyType = $type;

        return $this;
    }

    /**
     * @param string|null $name
     * @param string|null $type
     *
     * @return self
     */
    public function column(?string $name = null, ?string $type = null): self
    {
        $this->columnName = $name;
        $this->columnType ??= $type;

        return $this;
    }

    /**
     * @param \Articulate\Contracts\FieldCharacteristic ...$characteristics
     *
     * @return self
     */
    public function characteristics(FieldCharacteristic ...$characteristics): self
    {
        // Reset the characteristics
        $this->characteristics = [];

        // Cycle through and add them
        foreach ($characteristics as $characteristic) {
            $this->characteristics[$characteristic::class] = $characteristic;
        }

        return $this;
    }

    public function characteristic(FieldCharacteristic $characteristic): self
    {
        $this->characteristics[$characteristic::class] = $characteristic;

        return $this;
    }

    /**
     * @return array{
     *     propertyName: string,
     *     propertyType: string|null,
     *     columnName: string|null,
     *     columnType: string|null,
     *     characteristics: array<class-string<\Articulate\Contracts\FieldCharacteristic>, \Articulate\Contracts\FieldCharacteristic>|empty
     * }
     */
    public function toArray(): array
    {
        return [
            'propertyName'    => $this->propertyName,
            'propertyType'    => $this->propertyType,
            'columnName'      => $this->columnName,
            'columnType'      => $this->columnType,
            'characteristics' => $this->characteristics,
        ];
    }
}

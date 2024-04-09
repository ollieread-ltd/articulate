<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

/**
 * @template ValType of mixed
 */
final readonly class DefaultValue implements FieldCharacteristic
{
    /**
     * @var ValType
     */
    public mixed $value;

    /**
     * @param ValType $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
}

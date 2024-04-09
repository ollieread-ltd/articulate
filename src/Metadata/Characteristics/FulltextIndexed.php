<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

final readonly class FulltextIndexed implements FieldCharacteristic
{
    /**
     * @var bool|string|null
     */
    public bool|string|null $name;

    /**
     * @param bool|string|null $name
     */
    public function __construct(bool|string|null $name = null)
    {
        $this->name = $name;
    }
}

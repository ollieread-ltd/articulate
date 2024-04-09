<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

final readonly class UsingCollation implements FieldCharacteristic
{
    /**
     * @var string
     */
    public string $collation;

    /**
     * @param string $collation
     */
    public function __construct(string $collation)
    {
        $this->collation = $collation;
    }
}

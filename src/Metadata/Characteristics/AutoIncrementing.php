<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

final readonly class AutoIncrementing implements FieldCharacteristic
{
    /**
     * @var int|null
     */
    public ?int $startingFrom;

    /**
     * @param int|null $startingFrom
     */
    public function __construct(?int $startingFrom = null)
    {
        $this->startingFrom = $startingFrom;
    }
}

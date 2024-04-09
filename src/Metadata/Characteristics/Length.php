<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

final readonly class Length implements FieldCharacteristic
{
    /**
     * @var int
     */
    public int $length;

    /**
     * @param int $length
     */
    public function __construct(int $length)
    {
        $this->length = $length;
    }
}

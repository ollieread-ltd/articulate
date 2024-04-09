<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

final readonly class AfterColumn implements FieldCharacteristic
{
    /**
     * @var string
     */
    public string $column;

    /**
     * @param string $column
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }
}

<?php
declare(strict_types=1);

namespace Articulate\Metadata\Characteristics;

use Articulate\Contracts\FieldCharacteristic;

final readonly class UsingCharset implements FieldCharacteristic
{
    /**
     * @var string
     */
    public string $charset;

    /**
     * @param string $charset
     */
    public function __construct(string $charset)
    {
        $this->charset = $charset;
    }
}

<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties;

use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Types\IntegerType;

/**
 * @implements \Articulate\Contracts\PropertyType<mixed, int>
 */
final class IntegerPropertyType extends IntegerType implements PropertyType
{
}

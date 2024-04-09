<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties;

use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Types\BooleanType;

/**
 * @implements \Articulate\Contracts\PropertyType<mixed, bool>
 */
final class BooleanPropertyType extends BooleanType implements PropertyType
{
}

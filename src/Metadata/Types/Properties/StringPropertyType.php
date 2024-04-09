<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Properties;

use Articulate\Contracts\PropertyType;
use Articulate\Metadata\Types\StringType;

/**
 * @implements \Articulate\Contracts\PropertyType<mixed, string>
 */
final class StringPropertyType extends StringType implements PropertyType
{
}

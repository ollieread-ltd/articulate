<?php

namespace Articulate\Contracts;

use Illuminate\Database\Connection;

/**
 * Component Metadata
 *
 * The component metadata class is a subtype of {@see \Articulate\Contracts\Metadata}
 * specifically for components.
 *
 * @template MetaClass of object
 *
 * @extends \Articulate\Contracts\Metadata<MetaClass>
 */
interface ComponentMetadata extends Metadata
{
}

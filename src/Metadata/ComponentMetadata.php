<?php
declare(strict_types=1);

namespace Articulate\Metadata;

use Articulate\Contracts\ComponentMetadata as ComponentMetadataContract;

/**
 * Component Metadata
 *
 * @package Metadata
 *
 * @template ComponentClass of object
 *
 * @extends \Articulate\Metadata\Metadata<ComponentClass>
 * @implements \Articulate\Contracts\ComponentMetadata<ComponentClass>
 */
final class ComponentMetadata extends Metadata implements ComponentMetadataContract
{

}

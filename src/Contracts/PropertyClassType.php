<?php

namespace Articulate\Contracts;

/**
 * Property Type
 *
 * @package Metadata
 *
 * @template InType of mixed
 * @template ClassType of object
 *
 * @extends \Articulate\Contracts\PropertyType<InType, ClassType>
 */
interface PropertyClassType extends PropertyType
{
    /**
     * The class type this property is for
     *
     * @return class-string<ClassType>
     */
    public function class(): string;

    /**
     * Whether to match properties of the exact type
     *
     * @return bool
     */
    public function exactMatch(): bool;
}

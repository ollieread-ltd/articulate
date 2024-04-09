<?php

namespace Articulate\Contracts;

/**
 * Field Type
 *
 * @package Metadata
 *
 * @template InType of mixed
 * @template OutType of mixed
 */
interface FieldType
{
    /**
     * Get the name of the field type
     *
     * @return string
     */
    public function name(): string;

    /**
     * Cast a value to the appropriate type
     *
     * @param InType                                       $value
     * @param \Articulate\Contracts\Field<InType, OutType> $field
     * @param \Articulate\Contracts\Metadata<object>       $metadata
     *
     * @return OutType
     */
    public function cast(mixed $value, Field $field, Metadata $metadata): mixed;
}

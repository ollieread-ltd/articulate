<?php

namespace Articulate\Contracts;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * Column Type
 *
 * @package Metadata
 *
 * @template InType of mixed
 * @template OutType of mixed
 *
 * @extends \Articulate\Contracts\FieldType<InType, OutType>
 */
interface ColumnType extends FieldType
{
    /**
     * Define the column for the database
     *
     * @param \Illuminate\Database\Schema\Blueprint     $blueprint
     * @param \Articulate\Contracts\Field<mixed, mixed> $field
     * @param \Articulate\Contracts\Metadata<object>    $metadata
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function define(Blueprint $blueprint, Field $field, Metadata $metadata): ColumnDefinition;
}

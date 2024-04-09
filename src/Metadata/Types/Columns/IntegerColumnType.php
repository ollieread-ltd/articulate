<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Columns;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Metadata\Characteristics\AutoIncrementing;
use Articulate\Metadata\Characteristics\Big;
use Articulate\Metadata\Characteristics\Medium;
use Articulate\Metadata\Characteristics\Small;
use Articulate\Metadata\Characteristics\Tiny;
use Articulate\Metadata\Characteristics\Unsigned;
use Articulate\Metadata\Types\IntegerType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * @implements \Articulate\Contracts\ColumnType<mixed, int>
 */
final class IntegerColumnType extends IntegerType implements ColumnType
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
    public function define(Blueprint $blueprint, Field $field, Metadata $metadata): ColumnDefinition
    {
        // Figure out the correct method for the size of the column
        if ($field->is(Tiny::class)) {
            $creator = $blueprint->tinyInteger(...);
        } else if ($field->is(Small::class)) {
            $creator = $blueprint->smallInteger(...);
        } else if ($field->is(Medium::class)) {
            $creator = $blueprint->mediumInteger(...);
        } else if ($field->is(Big::class)) {
            $creator = $blueprint->bigInteger(...);
        } else {
            $creator = $blueprint->integer(...);
        }

        /**
         * @var \Closure(string $column, bool $autoIncrement, bool $unsigned): ColumnDefinition $creator
         */

        // Get the auto-incrementing characteristic, if present
        $increments = $field->as(AutoIncrementing::class);

        // Create the column definition
        $definition = $creator(
            $field->column(),
            $increments !== null,
            $field->is(Unsigned::class)
        );

        // If the auto-increment characteristic has a starting value, use that
        if ($increments?->startingFrom !== null) {
            $definition->startingValue($increments->startingFrom);
        }

        return $definition;
    }
}

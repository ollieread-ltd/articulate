<?php
declare(strict_types=1);

namespace Articulate\Metadata\Types\Columns;

use Articulate\Contracts\ColumnType;
use Articulate\Contracts\Field;
use Articulate\Contracts\Metadata;
use Articulate\Metadata\Characteristics\Length;
use Articulate\Metadata\Types\StringType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

/**
 * @implements \Articulate\Contracts\ColumnType<mixed, string>
 */
final class CharColumnType extends StringType implements ColumnType
{
    public const NAME = 'char';

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
        return $blueprint->char(
            $field->column(),
            $field->as(Length::class)?->length ?? null
        );
    }
}

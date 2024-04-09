<?php

namespace Articulate\Contracts;

use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;

/**
 * Entity Metadata
 *
 * The entity metadata class is a subtype of {@see \Articulate\Contracts\Metadata}
 * specifically for entities.
 *
 * @template MetaClass of object
 *
 * @extends \Articulate\Contracts\Metadata<MetaClass>
 */
interface EntityMetadata extends Metadata
{
    /**
     * Get the name database table name
     *
     * @return string
     */
    public function table(): string;

    /**
     * Get the name of the database connection
     *
     * @return string|null
     */
    public function connectionName(): ?string;

    /**
     * Get the database connection
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection(): ConnectionInterface;
}

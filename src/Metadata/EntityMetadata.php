<?php
declare(strict_types=1);

namespace Articulate\Metadata;

use Articulate\Contracts\EntityMetadata as EntityMetadataContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\Collection;

/**
 * Entity Metadata
 *
 * @package Metadata
 *
 * @template EClass of object
 *
 * @extends \Articulate\Metadata\Metadata<EClass>
 * @implements \Articulate\Contracts\EntityMetadata<EClass>
 */
final class EntityMetadata extends Metadata implements EntityMetadataContract
{
    /**
     * The database connection resolver
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    private static ConnectionResolverInterface $resolver;

    /**
     * Set the database connection resolver
     *
     * @param \Illuminate\Database\ConnectionResolverInterface $resolver
     *
     * @return void
     */
    public static function setConnectionResolver(ConnectionResolverInterface $resolver): void
    {
        self::$resolver = $resolver;
    }

    /**
     * Resolve the database connection
     *
     * @param string|null $connection
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected static function resolve(?string $connection = null): ConnectionInterface
    {
        return self::$resolver->connection($connection);
    }

    /**
     * The name of the database table
     *
     * @var string
     */
    private string $tableName;

    /**
     * The name of the connection
     *
     * @var string|null
     */
    private ?string $connectionName;

    /**
     * Create a new instance of the entity metadata
     *
     * @param class-string<EClass>                                                              $entityClass
     * @param string                                                                            $tableName
     * @param string|null                                                                       $connectionName
     * @param \Illuminate\Support\Collection<string, \Articulate\Contracts\Field<mixed, mixed>> $fields
     */
    public function __construct(
        string     $entityClass,
        Collection $fields,
        string     $tableName,
        ?string    $connectionName = null
    )
    {
        parent::__construct($entityClass, $fields);

        $this->tableName      = $tableName;
        $this->connectionName = $connectionName;
    }

    /**
     * Get the name database table name
     *
     * @return string
     */
    public function table(): string
    {
        return $this->tableName;
    }

    /**
     * Get the name of the database connection
     *
     * @return string|null
     */
    public function connectionName(): ?string
    {
        return $this->connectionName;
    }

    /**
     * Get the database connection
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection(): ConnectionInterface
    {
        return self::resolve($this->connectionName());
    }
}

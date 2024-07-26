<?php

namespace Articulate\Tests\Fixtures;

use Articulate\Metadata\Mapping;
use Articulate\Metadata\MetadataBuilder;
use Articulate\Metadata\Support\TouchEvent;

/**
 * User Mapping
 *
 * @extends \Articulate\Metadata\Mapping<\Articulate\Tests\Fixtures\User>
 */
class UserMapping extends Mapping
{
    /**
     * The class that is being mapped
     *
     * @return class-string<\Articulate\Tests\Fixtures\User>
     */
    public function class(): string
    {
        return User::class;
    }

    /**
     * Map the metadata
     *
     * @param \Articulate\Metadata\MetadataBuilder<\Articulate\Tests\Fixtures\User> $metadata
     *
     * @return void
     */
    public function map(MetadataBuilder $metadata): void
    {
        // Set the table
        $metadata->table('users');

        // Configure the fields
        $metadata->id();
        $metadata->string('name');
        $metadata->string('email')->unique();
        $metadata->string('password')->invisible();
        $metadata->string('rememberToken')->nullable();
        $metadata->carbon('emailVerifiedAt')->nullable();

        // The timestamps
        $metadata->carbon('createdAt')->touchable(TouchEvent::Created);
        $metadata->carbon('updatedAt')->touchable(TouchEvent::Updated)->nullable();
    }
}

<?php

namespace Workbench\Database\Metadata;

use Articulate\Metadata\Mapping;
use Articulate\Metadata\MetadataBuilder;
use Articulate\Metadata\Support\TouchEvent;
use Workbench\App\Entities\User;

class UserMapping extends Mapping
{
    public function class(): string
    {
        return User::class;
    }

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

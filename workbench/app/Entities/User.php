<?php
declare(strict_types=1);

namespace Workbench\App\Entities;

use _OLD\Metadata\Attributes\Entity;
use _OLD\Metadata\Attributes\ID;
use Carbon\Carbon;

#[Entity('users')]
final class User
{
    private int $id;

    private string $name;

    private string $email;

    private string $password;

    private ?string $rememberToken;

    private ?Carbon $emailVerifiedAt;

    private ?Carbon $createdAt;

    private ?Carbon $updatedAt;
}

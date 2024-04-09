<?php
declare(strict_types=1);

namespace Workbench\App\Entities;

use Articulate\Metadata\Attributes as Metadata;
use Articulate\Metadata\Support\TouchEvent;
use Carbon\Carbon;

#[Metadata\Entity('users')]
final class User
{
    #[Metadata\ID]
    private int $id;

    #[Metadata\Field]
    private string $name;

    #[Metadata\Field]
    private string $email;

    #[Metadata\Field, Metadata\Invisible]
    private string $password;

    #[Metadata\Field]
    private ?string $rememberToken;

    #[Metadata\Field]
    private ?Carbon $emailVerifiedAt;

    #[Metadata\Field, Metadata\Touchable(TouchEvent::Created)]
    private ?Carbon $createdAt;

    #[Metadata\Field, Metadata\Touchable(TouchEvent::Updated)]
    private ?Carbon $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(?string $rememberToken): User
    {
        $this->rememberToken = $rememberToken;
        return $this;
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?Carbon $emailVerifiedAt): User
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
        return $this;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?Carbon $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?Carbon $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}

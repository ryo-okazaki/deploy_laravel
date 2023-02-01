<?php

declare(strict_types=1);

namespace App\Command\Auth;

class StoreCommand
{
    public function __construct(
        private ?string $email,
        private ?string $name,
        private ?string $password,
    ) {}

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}

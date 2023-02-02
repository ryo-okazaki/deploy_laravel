<?php

declare(strict_types=1);

namespace App\Command\Password;

class EmailCommand
{
    public function __construct(
        private ?string $email,
        private ?string $token,
    ) {}

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}

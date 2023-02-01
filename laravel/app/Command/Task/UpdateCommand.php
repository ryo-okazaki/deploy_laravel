<?php

declare(strict_types=1);

namespace App\Command\Task;

class UpdateCommand
{
    public function __construct(
        private ?string $title,
        private ?string $status,
        private ?string $due_date,
    ) {}

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date;
    }
}

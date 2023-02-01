<?php

declare(strict_types=1);

namespace App\Command\Task;

class StoreCommand
{
    public function __construct(
        private ?string $title,
        private ?string $due_date,
    ) {}

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date;
    }
}

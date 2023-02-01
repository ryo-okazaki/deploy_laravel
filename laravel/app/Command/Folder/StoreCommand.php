<?php

declare(strict_types=1);

namespace App\Command\Folder;

class StoreCommand
{
    public function __construct(
        private ?string $title,
    ) {}

    public function getTitle(): ?string
    {
        return $this->title;
    }
}

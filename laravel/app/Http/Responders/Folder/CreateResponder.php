<?php

declare(strict_types=1);

namespace App\Http\Responders\Folder;

use App\Exceptions\UndefinedStatusException;
use App\Http\Payload;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class CreateResponder
{
    public function __construct(private ResponseFactory $responseFactory) {}

    public function handle(Payload $payload): Response
    {
        if ($payload->getStatus() === Payload::FOUND) {
            return $this->responseFactory->view('folders.create');
        }

        throw UndefinedStatusException::fromStatus($payload->getStatus());
    }
}

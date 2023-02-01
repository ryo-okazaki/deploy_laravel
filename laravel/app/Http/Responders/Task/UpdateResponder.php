<?php

declare(strict_types=1);

namespace App\Http\Responders\Task;

use App\Exceptions\UndefinedStatusException;
use App\Http\Payload;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class UpdateResponder
{
    public function __construct(private ResponseFactory $responseFactory) {}

    public function handle(Payload $payload): Response
    {
        if ($payload->getStatus() === Payload::STORED) {
            return $this->responseFactory->redirectToRoute('tasks.index', $payload->getOutput());
        }

        throw UndefinedStatusException::fromStatus($payload->getStatus());
    }
}

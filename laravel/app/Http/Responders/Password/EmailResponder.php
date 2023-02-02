<?php

declare(strict_types=1);

namespace App\Http\Responders\Password;

use App\Exceptions\UndefinedStatusException;
use App\Http\Payload;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class EmailResponder
{
    public function __construct(private ResponseFactory $responseFactory) {}

    public function handle(Payload $payload): Response
    {
        if ($payload->getStatus() === Payload::SUCCEED) {
            return $this->responseFactory->redirectToRoute('home');
        }

        if ($payload->getStatus() === Payload::FAILED) {
            return $this->responseFactory->redirectToRoute('home');
        }

        throw UndefinedStatusException::fromStatus($payload->getStatus());
    }
}

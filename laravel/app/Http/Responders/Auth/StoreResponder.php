<?php

declare(strict_types=1);

namespace App\Http\Responders\Auth;

use App\Exceptions\UndefinedStatusException;
use App\Http\Payload;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class StoreResponder
{
    public function __construct(private ResponseFactory $responseFactory) {}

    public function handle(Payload $payload): Response
    {
        if ($payload->getStatus() === Payload::SUCCEED) {
            return $this->responseFactory->view('home');
        }

        if ($payload->getStatus() === Payload::FAILED) {
            return $this->responseFactory->redirectToRoute('register');
        }

        throw UndefinedStatusException::fromStatus($payload->getStatus());
    }
}

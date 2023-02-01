<?php

declare(strict_types=1);

namespace App\Http\Responders\Home;

use App\Exceptions\UndefinedStatusException;
use App\Http\Payload;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class IndexResponder
{
    public function __construct(private ResponseFactory $responseFactory) {}

    public function handle(Payload $payload): Response
    {
        if ($payload->getStatus() === Payload::FOUND) {

            if (is_null($payload->getOutput())) {
                return $this->responseFactory->view('home');
            } else {
                return $this->responseFactory->view('tasks.index', $payload->getOutput());
            }
        }

        throw UndefinedStatusException::fromStatus($payload->getStatus());
    }
}
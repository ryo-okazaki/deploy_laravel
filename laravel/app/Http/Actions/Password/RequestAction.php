<?php

declare(strict_types=1);

namespace App\Http\Actions\Password;

use App\Http\Payload;
use App\Http\Responders\Password\RequestResponder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestAction
{
    public function __construct(private RequestResponder $responder) {}

    public function __invoke(Request $request): Response
    {
        $token = $request->route()->parameter('token');

        return $this->responder->handle(
            (new Payload())
                ->setStatus(Payload::FOUND)
                ->setOutput(compact('token'))
        );
    }
}

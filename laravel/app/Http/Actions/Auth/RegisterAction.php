<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Http\Payload;
use App\Http\Responders\Auth\RegisterResponder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterAction
{
    public function __construct(
        private RegisterResponder $responder
    ) {}

    public function __invoke(Request $request): Response
    {
        return $this->responder->handle(
            (new Payload())->setStatus(Payload::FOUND)
        );
    }
}

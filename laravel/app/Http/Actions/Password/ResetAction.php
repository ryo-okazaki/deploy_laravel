<?php

declare(strict_types=1);

namespace App\Http\Actions\Password;

use App\Http\Payload;
use App\Http\Responders\Password\ResetResponder;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetAction
{
    public function __construct(private ResetResponder $responder) {}

    public function __invoke(Request $request, string $token, User $user): Response
    {
        return $this->responder->handle(
            (new Payload())
                ->setStatus(Payload::FOUND)
                ->setOutput(compact('token', 'user'))
        );
    }
}

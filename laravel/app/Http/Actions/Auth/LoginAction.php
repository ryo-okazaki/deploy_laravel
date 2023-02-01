<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Command\Auth\LoginCommand;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responders\Auth\LoginResponder;
use App\Usecase\Auth\LoginUsecase;
use Symfony\Component\HttpFoundation\Response;

class LoginAction
{
    public function __construct(
        private LoginUsecase $usecase,
        private LoginResponder $responder
    ) {}

    public function __invoke(LoginRequest $request): Response
    {
        $command = new LoginCommand(
            $request->get('email'),
            $request->get('password'),
        );

        return $this->responder->handle($this->usecase->run($command, $request));
    }
}

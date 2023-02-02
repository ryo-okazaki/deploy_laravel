<?php

declare(strict_types=1);

namespace App\Http\Actions\Password;

use App\Command\Password\EmailCommand;
use App\Http\Requests\Password\EmailRequest;
use App\Http\Responders\Password\EmailResponder;
use App\Usecase\Password\EmailUsecase;
use Symfony\Component\HttpFoundation\Response;

class EmailAction
{
    public function __construct(
        private EmailUsecase $usecase,
        private EmailResponder $responder,
    ) {}

    public function __invoke(EmailRequest $request): Response
    {
        $command = new EmailCommand(
            email: $request->get('email'),
            token: $request->get('_token'),
        );

        return $this->responder->handle($this->usecase->run($command));
    }
}

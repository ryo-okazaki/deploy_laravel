<?php

declare(strict_types=1);

namespace App\Http\Actions\Password;

use App\Command\Password\UpdateCommand;
use App\Http\Requests\Password\UpdateRequest;
use App\Http\Responders\Password\UpdateResponder;
use App\Usecase\Password\UpdateUsecase;
use Symfony\Component\HttpFoundation\Response;

class UpdateAction
{
    public function __construct(
        private UpdateUsecase   $usecase,
        private UpdateResponder $responder,
    ) {}

    public function __invoke(UpdateRequest $request): Response
    {
        $command = new UpdateCommand(
            email: $request->get('email'),
            password: $request->get('password'),
        );

        return $this->responder->handle($this->usecase->run($command));
    }
}

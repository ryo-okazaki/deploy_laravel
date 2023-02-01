<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Command\Auth\StoreCommand;
use App\Http\Requests\Auth\StoreRequest;
use App\Http\Responders\Auth\StoreResponder;
use App\Usecase\Auth\StoreUsecase;
use Symfony\Component\HttpFoundation\Response;

class StoreAction
{
    public function __construct(
        private StoreUsecase $usecase,
        private StoreResponder $responder
    ) {}

    public function __invoke(StoreRequest $request): Response
    {
        $command = new StoreCommand(
            $request->get('name'),
            $request->get('email'),
            $request->get('password'),
        );

        return $this->responder->handle($this->usecase->run($command, $request));
    }
}

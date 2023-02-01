<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Http\Responders\Auth\LogoutResponder;
use App\Usecase\Auth\LogoutUsecase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutAction
{
    public function __construct(
        private LogoutUsecase $usecase,
        private LogoutResponder $responder
    ) {}
    public function __invoke(Request $request): Response
    {
        return $this->responder->handle($this->usecase->run($request));
    }
}

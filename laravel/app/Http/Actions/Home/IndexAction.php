<?php

declare(strict_types=1);

namespace App\Http\Actions\Home;

use App\Http\Requests\Home\IndexRequest;
use App\Http\Responders\Home\IndexResponder;
use App\Usecase\Home\IndexUsecase;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexAction extends Controller
{
    public function __construct(
        private IndexUsecase $usecase,
        private IndexResponder $responder,
    ) {}

    public function __invoke(IndexRequest $request): Response
    {
        return $this->responder->handle($this->usecase->run());
    }
}

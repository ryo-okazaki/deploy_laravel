<?php

declare(strict_types=1);

namespace App\Http\Actions\Folder;

use App\Http\Payload;
use App\Http\Requests\Folder\CreateRequest;
use App\Http\Responders\Folder\CreateResponder;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class CreateAction extends Controller
{
    public function __construct(
        private CreateResponder $responder,
    ) {}

    public function __invoke(CreateRequest $request): Response
    {
        return $this->responder->handle(
            (new Payload())->setStatus(Payload::FOUND)
        );
    }
}

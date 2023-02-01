<?php

declare(strict_types=1);

namespace App\Http\Actions\Task;

use App\Http\Payload;
use App\Http\Requests\Task\CreateRequest;
use App\Http\Responders\Task\CreateResponder;
use App\Models\Folder;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class CreateAction extends Controller
{
    public function __construct(
        private CreateResponder $responder,
    ) {}

    public function __invoke(CreateRequest $request, Folder $folder): Response
    {
        return $this->responder->handle(
            (new Payload())
                ->setStatus(Payload::FOUND)
                ->setOutput(compact('folder'))
        );
    }
}

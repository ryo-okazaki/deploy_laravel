<?php

declare(strict_types=1);

namespace App\Http\Actions\Folder;

use App\Command\Folder\StoreCommand;
use App\Http\Requests\Folder\StoreRequest;
use App\Http\Responders\Folder\StoreResponder;
use App\Models\Folder;
use App\Usecase\Folder\StoreUsecase;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class StoreAction extends Controller
{
    public function __construct(
        private StoreUsecase $usecase,
        private StoreResponder $responder,
    ) {}

    public function __invoke(StoreRequest $request, Folder $folder): Response
    {
        $command = new StoreCommand(
            $request->get('title'),
        );

        return $this->responder->handle($this->usecase->run($command));
    }
}

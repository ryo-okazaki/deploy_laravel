<?php

declare(strict_types=1);

namespace App\Http\Actions\Task;

use App\Command\Task\StoreCommand;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Responders\Task\StoreResponder;
use App\Models\Folder;
use App\Usecase\Task\StoreUsecase;
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
            $request->get('due_date'),
        );

        return $this->responder->handle($this->usecase->run($command, $folder));
    }
}

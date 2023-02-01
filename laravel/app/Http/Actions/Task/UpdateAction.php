<?php

declare(strict_types=1);

namespace App\Http\Actions\Task;

use App\Command\Task\UpdateCommand;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Responders\Task\UpdateResponder;
use App\Models\Folder;
use App\Models\Task;
use App\Usecase\Task\UpdateUsecase;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class UpdateAction extends Controller
{
    public function __construct(
        private UpdateUsecase $usecase,
        private UpdateResponder $responder,
    ) {}

    public function __invoke(UpdateRequest $request, Folder $folder, Task $task): Response
    {
        $command = new UpdateCommand(
            $request->get('title'),
            $request->get('status'),
            $request->get('due_date'),
        );

        return $this->responder->handle($this->usecase->run($command, $folder, $task));
    }
}

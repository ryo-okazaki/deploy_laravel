<?php

declare(strict_types=1);

namespace App\Http\Actions\Task;

use App\Http\Payload;
use App\Http\Responders\Task\EditResponder;
use App\Models\Folder;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class EditAction extends Controller
{
    public function __construct(
        private EditResponder $responder,
    ) {}

    public function __invoke(Request $request, Folder $folder, Task $task): Response
    {
        return $this->responder->handle(
            (new Payload())
                ->setStatus(Payload::FOUND)
                ->setOutput(compact('folder', 'task'))
        );
    }
}

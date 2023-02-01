<?php

declare(strict_types=1);

namespace App\Http\Actions\Task;

use App\Http\Requests\Task\IndexRequest;
use App\Http\Responders\Task\IndexResponder;
use App\Models\Folder;
use App\Usecase\Task\IndexUsecase;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexAction extends Controller
{
    public function __construct(
        private IndexUsecase $usecase,
        private IndexResponder $responder,
    ) {}

    public function __invoke(IndexRequest $request, Folder $folder): Response
    {
        return $this->responder->handle($this->usecase->run($folder));
    }
}

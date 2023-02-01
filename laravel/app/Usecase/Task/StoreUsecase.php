<?php

declare(strict_types=1);

namespace App\Usecase\Task;

use App\Command\Task\StoreCommand;
use App\Http\Payload;
use App\Models\Folder;
use App\Models\Task;

class StoreUsecase
{
    public function run(StoreCommand $command, Folder $folder): Payload
    {
        $task = new Task();
        $task->title = $command->getTitle();
        $task->due_date = $command->getDueDate();

        $folder->tasks()->save($task);

        return (new Payload())
            ->setStatus(Payload::STORED)
            ->setOutput(compact('folder'));
    }
}

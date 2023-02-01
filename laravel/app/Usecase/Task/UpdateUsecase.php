<?php

declare(strict_types=1);

namespace App\Usecase\Task;

use App\Command\Task\UpdateCommand;
use App\Http\Payload;
use App\Models\Folder;
use App\Models\Task;

class UpdateUsecase
{
    public function run(UpdateCommand $command, Folder $folder, Task $task): Payload
    {
        abort_if($folder->id !== $task->folder_id, 404);

        $task->title = $command->getTitle();
        $task->status = $command->getStatus();
        $task->due_date = $command->getDueDate();
        $task->save();

        return (new Payload())
            ->setStatus(Payload::STORED)
            ->setOutput(compact('folder'));
    }
}

<?php

declare(strict_types=1);

namespace App\Usecase\Task;

use App\Http\Payload;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class IndexUsecase
{
    public function run(Folder $folder): Payload
    {
        $folders = Auth::user()->folders()->get();

        $tasks = $folder->tasks()->get();

        $current_folder_id = $folder->id;

        return (new Payload())
            ->setStatus(Payload::FOUND)
            ->setOutput(compact(
                'folders',
                'current_folder_id',
                'tasks'
            ));
    }
}

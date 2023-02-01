<?php

declare(strict_types=1);

namespace App\Usecase\Home;

use App\Http\Payload;
use Illuminate\Support\Facades\Auth;

class IndexUsecase
{
    public function run(): Payload
    {
        $folder = Auth::user()->folders()->first();

        if (!empty($folder)) {
            $current_folder_id = $folder->id;
            $tasks = $folder->tasks;

            return (new Payload())
                ->setStatus(Payload::FOUND)
                ->setOutput(compact(
                    'folder',
                    'current_folder_id',
                    'tasks'
                ));
        } else {
            return (new Payload())
                ->setStatus(Payload::FOUND);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Usecase\Folder;

use App\Command\Folder\StoreCommand;
use App\Http\Payload;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class StoreUsecase
{
    public function run(StoreCommand $command): Payload
    {
        $folder = new Folder();

        $folder->title = $command->getTitle();

        Auth::user()->folders()->save($folder);

        return (new Payload())
            ->setStatus(Payload::STORED)
            ->setOutput(compact('folder'));
    }
}

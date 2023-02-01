<?php

declare(strict_types=1);

namespace App\Usecase\Auth;

use App\Command\Auth\StoreCommand;
use App\Http\Payload;
use App\Http\Requests\Auth\StoreRequest;
use Illuminate\Support\Facades\Auth;

class StoreUsecase
{
    public function run(StoreCommand $command, StoreRequest $request): Payload
    {
        $userData = [
            'email' => $command->getEmail(),
            'password' => $command->getPassword(),
        ];

        if (Auth::attempt($userData)) {
            $request->session()->regenerate();

            return (new Payload())
                ->setStatus(Payload::SUCCEED);
        }

        return (new Payload())
            ->setStatus(Payload::FAILED);
    }
}

<?php

declare(strict_types=1);

namespace App\Usecase\Auth;

use App\Command\Auth\StoreCommand;
use App\Http\Payload;
use App\Http\Requests\Auth\StoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreUsecase
{
    public function run(StoreCommand $command): Payload
    {
        $user = User::create([
            'name' => $command->getName(),
            'email' => $command->getEmail(),
            'password' => $command->getPassword(),
        ]);

        Auth::guard()->login($user);

        return (new Payload())
            ->setStatus(Payload::SUCCEED);
    }
}

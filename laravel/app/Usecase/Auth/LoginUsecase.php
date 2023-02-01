<?php

declare(strict_types=1);

namespace App\Usecase\Auth;

use App\Command\Auth\LoginCommand;
use App\Http\Payload;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class LoginUsecase
{
    public function run(LoginCommand $command, LoginRequest $request): Payload
    {
        $userData = [
            'email' => $command->getEmail(),
            'password' => $command->getPassword(),
        ];

        if (Auth::attempt($userData, false)) {
            $request->session()->regenerate();

//            $folder = Folder::where('user_id', Auth::id())->first();
//            $current_folder_id = $folder->id;

            return (new Payload())
                ->setStatus(Payload::SUCCEED);
        }

        return (new Payload())
            ->setStatus(Payload::FAILED);
    }
}

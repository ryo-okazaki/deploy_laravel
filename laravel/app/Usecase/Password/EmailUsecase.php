<?php

declare(strict_types=1);

namespace App\Usecase\Password;

use App\Command\Password\EmailCommand;
use App\Http\Payload;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailUsecase
{
    public function run(EmailCommand $command): Payload
    {
        $user = User::where('email', $command->getEmail())->first();

        try {
            if ($user) {
                Mail::to($user)->send(new ResetPassword($user, $command->getToken()));
            } else {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            \Log::debug($e);

            return (new Payload())
                ->setStatus(Payload::FAILED);
        }

        return (new Payload())
            ->setStatus(Payload::SUCCEED);
    }
}

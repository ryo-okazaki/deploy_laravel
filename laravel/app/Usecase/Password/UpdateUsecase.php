<?php

declare(strict_types=1);

namespace App\Usecase\Password;

use App\Command\Password\UpdateCommand;
use App\Http\Payload;
use App\Mail\NotifyUpdatePassword;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Mail;

class UpdateUsecase
{
    public function __construct(private ConnectionInterface $connection) {}

    public function run(UpdateCommand $command): Payload
    {
        $user = User::where('email', $command->getEmail())->first();

        try {
            $this->connection->beginTransaction();

            $user->fill([
                'password' => $command->getPassword()
            ]);
            $user->save();

            Mail::to($user)->send(new NotifyUpdatePassword($user));

            $this->connection->commit();
        } catch (\Exception $e) {
            \Log::debug($e);
            $this->connection->rollBack();

            return (new Payload())
                ->setStatus(Payload::FAILED);
        }

        return (new Payload())
            ->setStatus(Payload::SUCCEED);
    }
}

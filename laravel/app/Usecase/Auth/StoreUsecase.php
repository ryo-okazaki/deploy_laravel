<?php

declare(strict_types=1);

namespace App\Usecase\Auth;

use App\Command\Auth\StoreCommand;
use App\Http\Payload;
use App\Mail\Register;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StoreUsecase
{
    public function __construct(private ConnectionInterface $connection) {}

    public function run(StoreCommand $command): Payload
    {
        try {
            $this->connection->beginTransaction();

            $user = User::create([
                'name' => $command->getName(),
                'email' => $command->getEmail(),
                'password' => Hash::make($command->getPassword()),
            ]);

            Mail::to($user)->send(new Register($user));

            $this->connection->commit();
        } catch (\Exception $e) {
            \Log::debug($e);
            $this->connection->rollBack();

            return (new Payload())
                ->setStatus(Payload::FAILED);
        }

        Auth::guard()->login($user);

        return (new Payload())
            ->setStatus(Payload::SUCCEED);
    }
}

<?php

declare(strict_types=1);

namespace App\Usecase\Auth;

use App\Http\Payload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutUsecase
{
    public function run(Request $request): Payload
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return (new Payload())
            ->setStatus(Payload::SUCCEED);
    }
}

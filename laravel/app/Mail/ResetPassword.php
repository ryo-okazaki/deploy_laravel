<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private User $user,
        private string $token
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'パスワード変更フォーム',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.password_reset',
            with: [
                'user' => $this->user,
                'token' => $this->token,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

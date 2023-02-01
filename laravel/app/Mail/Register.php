<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "会員登録 {$this->user->name}様",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.register-notification',
            with: [
                'createdAt' => $this->user->created_at
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

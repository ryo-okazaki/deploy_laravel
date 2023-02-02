<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyUpdatePassword extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'パスワード変更のお知らせ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.update_password_notification',
            with: [
                'user' => $this->user
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

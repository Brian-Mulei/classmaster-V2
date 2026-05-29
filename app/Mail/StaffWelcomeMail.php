<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $schoolName,
        public readonly string $staffName,
        public readonly string $username,
        public readonly string $temporaryPassword,
        public readonly string $loginUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ClassMaster account — ' . $this->schoolName,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.staff-welcome',
        );
    }
}

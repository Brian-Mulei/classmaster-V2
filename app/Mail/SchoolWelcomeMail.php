<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Tenant $tenant,
        public readonly string $loginUrl,
        public readonly string $adminUsername,
        public readonly string $adminPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ClassMaster school is ready — ' . $this->tenant->name,
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.school-welcome');
    }
}

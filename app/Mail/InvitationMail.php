<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation,
        public string $roleName,
        public string $companyName,
        public string $inviterName,
        public string $inviterEmail
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are invited to join '.$this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
            with: [
                'acceptUrl' => route('invitation.accept', $this->invitation->token),
                'companyName' => $this->companyName,
                'roleName' => $this->roleName,
                'inviterName' => $this->inviterName,
                'inviterEmail' => $this->inviterEmail,
                'invitedEmail' => $this->invitation->email,
                'expiresAt' => $this->invitation->expires_at,
            ],
        );
    }
}

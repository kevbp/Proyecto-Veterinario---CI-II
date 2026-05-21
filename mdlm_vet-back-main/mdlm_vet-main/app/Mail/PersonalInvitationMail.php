<?php

namespace App\Mail;

use App\Models\Personal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PersonalInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $registrationUrl;

    public function __construct(public readonly Personal $personal)
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $this->registrationUrl = "{$frontendUrl}/registro-personal?token={$this->personal->invitation_token}";
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido al equipo - Configura tu acceso',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.personal_invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

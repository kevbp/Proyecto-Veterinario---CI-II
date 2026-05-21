<?php

namespace App\Mail;

use App\Models\Propietario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClienteInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Propietario $propietario,
        public readonly string $registrationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido a Veterinaria - Registra tu cuenta',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cliente-invitation',
        );
    }
}

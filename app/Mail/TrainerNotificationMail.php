<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trainerName;
    public $apprenticeName;

    /**
     * Create a new message instance.
     */
    public function __construct($trainerName, $apprenticeName)
    {
        $this->trainerName = $trainerName;
        $this->apprenticeName = $apprenticeName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo aprendiz asignado'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.trainerNotification',
            with: [
                'trainerName' => $this->trainerName,
                'apprenticeName' => $this->apprenticeName,
            ]
        );
    }
    

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

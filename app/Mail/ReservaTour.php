<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaTour extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;
    public $cliente;
    public $pagina;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $reserva
     * @param  mixed  $cliente
     * @param  string|null  $pagina
     */
    public function __construct($reserva, $cliente, $pagina = null)
    {
        $this->reserva = $reserva;
        $this->cliente = $cliente;
        $this->pagina = $pagina;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu reserva ha sido confirmada'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reserva',
            with: [
                'reserva' => $this->reserva,
                'cliente' => $this->cliente,
                'pagina' => $this->pagina,
            ]
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

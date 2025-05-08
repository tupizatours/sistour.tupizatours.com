<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class ReservaTour extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;
    public $cliente;
    public $pagina;
    public $turistasAdicionales;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $reserva
     * @param  mixed  $cliente
     * @param  string|null  $pagina
     * @param  array  $turistasAdicionales
     * @param  string|null  $pdfPath
     */
    public function __construct($reserva, $cliente, $pagina = null, $turistasAdicionales = [], $pdfPath = null)
    {
        $this->reserva = $reserva;
        $this->cliente = $cliente;
        $this->pagina = $pagina;
        $this->turistasAdicionales = $turistasAdicionales;
        $this->pdfPath = $pdfPath;
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
                'turistas_adicionales' => $this->turistasAdicionales,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        if ($this->pdfPath) {
            return [
                Attachment::fromPath($this->pdfPath)
                    ->as('Resumen_Reserva.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}

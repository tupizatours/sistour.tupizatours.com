<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.reserva_confirmada')
            ->subject('Tu reserva ha sido confirmada')
            ->attach($this->pdfPath, [
                'as' => 'Resumen_Reserva.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

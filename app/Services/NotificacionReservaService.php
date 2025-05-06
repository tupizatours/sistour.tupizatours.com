<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\Reserva\Resercliente;

class NotificacionReservaService
{
    public static function enviarCorreoReservaConfirmada($reserva, $pagina = null)
    {
        $cliente = Resercliente::where('reserva_id', $reserva->id)
            ->where('esPrincipal', true)
            ->first();

        $turistas_adicionales = [];

        if ($reserva->can_per > 1) {
            $otros = Resercliente::where('reserva_id', $reserva->id)
                ->where('esPrincipal', false)
                ->get();

            foreach ($otros as $turista) {
                $turistas_adicionales[] = [
                    'link' => route('venresclis.edit', $turista->id),
                ];
            }
        }

        $correoData = [
            'reserva' => $reserva,
            'cliente' => $cliente,
            'turistas_adicionales' => $turistas_adicionales,
            'pagina' => $pagina,
        ];

        try {
            Mail::send('email.reserva', $correoData, function($message) use ($cliente) {
                $message->to($cliente->correo, $cliente->nombres)
                        ->subject('Tu reserva ha sido confirmada');
            });
        } catch (\Exception $e) {
            \Log::error('No se pudo enviar el correo de reserva: ' . $e->getMessage());
        }
    }
}

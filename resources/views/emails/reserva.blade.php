<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <style>
        body {
            background-color: #ececec;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            color: #2c3e50;
        }
        h2 {
            color: #16a085;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        .details {
            background-color: #f6f8fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .details p {
            margin: 6px 0;
        }
        .button {
            display: inline-block;
            background-color: #2980b9;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
        ul.turistas {
            padding: 0;
            margin: 20px 0;
        }
        ul.turistas li {
            list-style: none;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Tu reserva ha sido creada con éxito!</h2>

        <p>Hola {{ $cliente->nombres }} {{ $cliente->apellidos }},</p>

        <p>Gracias por reservar con Tupiza Tour. Aquí te dejamos el resumen de tu experiencia:</p>

        <div class="details">
            <p><strong>Tour:</strong> {{ $reserva->tour->titulo }}</p>
            <p><strong>Código de reserva:</strong> {{ $reserva->codigo }}</p>
            <p><strong>Fecha del tour:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</p>
            <p><strong>Participantes:</strong> {{ $reserva->can_per }}</p>
            <p><strong>Total pagado:</strong> Bs. {{ number_format($reserva->total, 2, '.', '') }}</p>
            <p><strong>Estado:</strong> {{ $reserva->estado == 1 ? 'Pendiente de verificación' : 'Confirmada' }}</p>
        </div>

        @if($reserva->estado == 1)
            <p style="text-align: center;">
                <a href="{{ route('reservas.update.external', $reserva->id) }}" class="button" style="background-color: #27ae60;">
                    Subir comprobante de pago
                </a>
            </p>
        @endif

        @if(isset($turistas_adicionales) && count($turistas_adicionales) > 0)
            <p>Faltan completar los datos de los siguientes turistas adicionales:</p>
            <ul class="turistas">
                @foreach($turistas_adicionales as $index => $turista)
                    <li>
                        <a href="{{ $turista['link'] }}" class="button">Completar datos del Turista {{ $index + 1 }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <p>Te estaremos contactando para coordinar los detalles. Si tienes dudas, responde a este correo.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Tupiza Tour · Todos los derechos reservados.
        </div>
    </div>
</body>
</html>

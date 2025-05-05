<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <style type="text/css">
        div, p, a, li, td {
            -webkit-text-size-adjust:none;
        }
        .ReadMsgBody, .ExternalClass {
            width: 100%;
            background-color: #cecece;
        }
        body {
            width: 100%;
            height: 100%;
            background-color: #cecece;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: 'Montserrat', sans-serif;
        }
        html {
            width: 100%;
        }
        img {
            border: none;
            display: block;
            max-width: 100%;
            height: auto;
        }
        .container {
            background-color: #ffffff;
            width: 600px;
            max-width: 100%;
            margin: auto;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .details {
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: left;
        }
        .button {
            display: inline-block;
            padding: 12px 18px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
        }
        .footer {
            font-size: 12px;
            color: #888;
            text-align: center;
            padding-top: 15px;
        }
        li {
            list-style: none;
            margin: 0;
            text-transform: center;
        }
        @media only screen and (max-width: 640px) {
            .container {
                width: 90%;
                padding: 15px;
            }
            .button {
                padding: 10px 15px;
            }
        }

        ul.turistas-lista {
            padding: 0;
            margin: 20px 0;
            text-align: center;
        }

        ul.turistas-lista li {
            list-style: none;
            margin-bottom: 12px;
        }

        ul.turistas-lista li a.button {
            margin: auto;
            display: inline-block;
        }

    </style>
</head>
<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td align="center">
                <div class="container">
                    <h2 style="color: #2c3e50;">¡Tu reserva ha sido confirmada!</h2>
                    <p>Hola {{ $data['nombre'] }} {{ $data['apellidos'] }},</p>
                    <p>Tu reserva para el tour ha sido confirmada exitosamente. Aquí están los detalles:</p>

                    <div class="details">
                        <p><strong>Código de Reserva:</strong> {{ $data['codigo_reserva'] }}</p>
                        <p><strong>Fecha del Tour:</strong> {{ $data['fecha_reserva'] }}</p>
                        <p><strong>Cantidad de Personas:</strong> {{ $data['cantidad_personas'] }}</p>
                        <p><strong>Monto Pagado:</strong> Bs. {{ number_format($data['monto_pagado'], 2, '.', '') }}</p>
                        <p><strong>Total c/u:</strong> Bs. {{ number_format($data['total'], 2, '.', '') }}</p>
                        <p><strong>Estado:</strong> {{ $data['estado'] }}</p>
                    </div>

                    @if(count($data['turistas_adicionales']) > 0 && $data['pagina'] != 'resclis')
                        <p>Los siguientes turistas adicionales deben completar sus datos:</p>
                        <ul class="turistas-lista">
                            @foreach($data['turistas_adicionales'] as $index => $turista)
                                <li>
                                    <a href="{{ $turista['link'] }}" class="button">
                                        Completar Datos {{ $index + 1 }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <p>¡Gracias por confiar en nosotros!</p>

                    <div class="footer">
                        <p>&copy; {{ date('Y') }} Tupiza Tour - Todos los derechos reservados.</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>

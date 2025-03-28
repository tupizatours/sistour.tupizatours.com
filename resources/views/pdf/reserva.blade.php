<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Reserva</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; }
        .logo { width: 150px; margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>

    <img src="{{ public_path('assets/images/logo-icon.png') }}" class="logo" alt="Logo">

    <h2>Resumen de Reserva: {{ $reserva->codigo }}</h2>

    <div class="section">
        <p><span class="bold">Tour:</span> {{ $reserva->tour->titulo }}</p>
        <p><span class="bold">Precio por persona:</span> Bs. {{ number_format($reserva->pre_per, 2, '.', '') }}</p>
        <p><span class="bold">Fecha del tour:</span> {{ date('d/m/Y', strtotime($reserva->fecha)) }}</p>
        <p><span class="bold">Fecha de reserva:</span> {{ date('d/m/Y', strtotime($reserva->created_at)) }}</p>
    </div>

    <div class="section">
        <h4>Datos del turista</h4>
        <p>{{ $cliente->nombres }} {{ $cliente->apellidos }} | {{ $cliente->nacionalidad }}</p>
        <p>Email: {{ $cliente->correo }}</p>
        <p>Documento: {{ $cliente->documento }} | Celular: {{ $cliente->celular }}</p>
    </div>

    <div class="section">
        <h4>Preferencias</h4>
        <p><strong>Alergias:</strong>
            @foreach($alergias as $item)
                <span>{{ $item->titulo }}</span>@if (!$loop->last), @endif
            @endforeach
        </p>
        <p><strong>Alimentación:</strong>
            @foreach($alimentos as $item)
                <span>{{ $item->titulo }}</span>@if (!$loop->last), @endif
            @endforeach
        </p>
    </div>

    <div class="section">
        <h4>Servicios Incluidos</h4>

        @if(count($habitaciones))
        <h5>Hoteles</h5>
        <table>
            <thead><tr><th>Hotel</th><th>Habitación</th><th>Precio</th></tr></thead>
            <tbody>
                @foreach($habitaciones as $hab)
                    <tr>
                        <td>{{ $hab['hotel'] }}</td>
                        <td>{{ $hab['name'] }}</td>
                        <td>Bs. {{ number_format($hab['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @foreach(['tickets' => 'Tickets', 'accesorios' => 'Accesorios', 'servicios' => 'Servicios'] as $grupo => $titulo)
            @if(count($$grupo))
            <h5>{{ $titulo }}</h5>
            <table>
                <thead><tr><th>Nombre</th><th>Precio</th></tr></thead>
                <tbody>
                    @foreach($$grupo as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>Bs. {{ number_format($item['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        @endforeach

    </div>

    <div class="section">
        <h4>Nota adicional</h4>
        <p>{{ $cliente->nota }}</p>
    </div>

</body>
</html>

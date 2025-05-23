<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Reserva</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        h2, h4, h5 { margin: 10px 0; }
        h2 { text-align: center; }
        .logo { width: 150px; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto; }
        .section { margin-bottom: 25px; }
        .bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        .info { margin: 0; padding: 0; }
    </style>
</head>
<body>

    <img src="{{ public_path('assets/images/logo-img.png') }}" class="logo" alt="Logo">

    <h2>Resumen de Reserva: {{ $reserva->codigo }}</h2>

    <div class="section">
        <p class="info"><span class="bold">Tour:</span> {{ $reserva->tour->titulo }}</p>
        <p class="info"><span class="bold">Precio por persona:</span> Bs. {{ number_format($reserva->pre_per, 2, '.', '') }}</p>
        <p class="info"><span class="bold">Fecha del tour:</span> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</p>
        <p class="info"><span class="bold">Fecha de reserva:</span> {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h4>Datos del Turista</h4>
        <p class="info">{{ $cliente->nombres }} {{ $cliente->apellidos }} ({{ $cliente->nacionalidad }})</p>
        <p class="info">Email: {{ $cliente->correo }}</p>
        <p class="info">Documento: {{ $cliente->documento }} | Celular: {{ $cliente->celular }}</p>
    </div>

    <div class="section">
        <h4>Preferencias</h4>
        <p class="info"><span class="bold">Alergias:</span>
            @forelse($alergias as $item)
                {{ $item->titulo }}@if (!$loop->last), @endif
            @empty
                Ninguna registrada
            @endforelse
        </p>
        <p class="info"><span class="bold">Alimentación:</span>
            @forelse($alimentos as $item)
                {{ $item->titulo }}@if (!$loop->last), @endif
            @empty
                No especificada
            @endforelse
        </p>
    </div>

    <div class="section">
        <h4>Servicios Incluidos</h4>

        @if(!empty($habitaciones) && count($habitaciones))
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

        @foreach (['tickets' => 'Tickets', 'accesorios' => 'Accesorios', 'servicios' => 'Servicios'] as $grupo => $titulo)
            @php $grupoData = $$grupo ?? collect(); @endphp
            @if($grupoData->isNotEmpty())
                <h5>{{ $titulo }}</h5>
                <table>
                    <thead><tr><th>Nombre</th><th>Precio</th></tr></thead>
                    <tbody>
                        @foreach($grupoData as $item)
                            <tr>
                                <td>{{ $item['name'] ?? '-' }}</td>
                                <td>Bs. {{ number_format($item['price'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>


    @if(!empty($cliente->total))
    <div class="section">
        <h4>Total</h4>
        <p>Bs. <span class="bold"> {{ $cliente->total }} </span></p>
    </div>
    @endif

    @if(!empty($cliente->nota))
    <div class="section">
        <h4>Nota Adicional</h4>
        <p>{{ $cliente->nota }}</p>
    </div>
    @endif

</body>
</html>

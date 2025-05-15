<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Tránsito</title>
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

    <img src="{{ public_path('assets/images/logo-icon.png') }}" class="logo" alt="Logo">

    <h2>Resumen de Reserva: {{ $reserva->codigo }}</h2>

    <div class="section">
        <p class="info"><span class="bold">Tour:</span> {{ $reserva->tour->titulo }}</p>
        <p class="info"><span class="bold">Privado:</span> {{ $reserva->tprivado ? 'Sí' : 'No' }}</p>
        <p class="info"><span class="bold">Fecha del tour:</span> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</p>
        <p class="info"><span class="bold">Fecha de reserva:</span> {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h4>Turistas</h4>
        <table>
            <thead><tr>
                <th>Nombre</th>
                <th>Nacionalidad</th>
                <th>Edad</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Celular</th>
            </tr></thead>
            <tbody>
                @foreach($resclis as $t)
                    <tr>
                        <td>{{ $t->nombres }} {{ $t->apellidos }}</td>
                        <td>{{ $t->nacionalidad }}</td>
                        <td>{{ $t->edad ?? '-' }}</td>
                        <td>{{ $t->documento ?? '-' }}</td>
                        <td>{{ $t->correo }}</td>
                        <td>{{ $t->celular }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php
        $cliente = $cliente ?? null;

        $hab_data   = collect($habitaciones ?? []);
        $tickets    = collect($tickets ?? []);
        $accesorios = collect($accesorios ?? []);
        $servicios  = collect($servicios ?? []);

        $alergias   = collect($alergias ?? []);
        $alimentos  = collect($alimentos ?? []);
    @endphp


    <div class="section">
        <h4>Preferencias</h4>
        <p class="info"><span class="bold">Alergias:</span>
            @forelse($alergias as $item)
                {{ $item }}@if (!$loop->last), @endif
            @empty
                Ninguna registrada
            @endforelse
        </p>
        <p class="info"><span class="bold">Alimentación:</span>
            @forelse($alimentos as $item)
                {{ $item }}@if (!$loop->last), @endif
            @empty
                No especificada
            @endforelse
        </p>
    </div>

    <div class="section">
        <h4>Servicios Incluidos</h4>

        @if(!empty($hab_data))
            <h5>Hoteles</h5>
            <table>
                <thead><tr><th>Día</th><th>Habitación</th><th>Precio</th></tr></thead>
                <tbody>
                    @foreach($hab_data as $hab)
                        <tr>
                            <td>Día {{ $hab['dia'] }}</td>
                            <td>{{ $hab['name'] }}</td>
                            <td>Bs. {{ number_format($hab['price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @foreach (['tickets' => 'Tickets', 'accesorios' => 'Accesorios', 'servicios' => 'Servicios'] as $key => $label)
            @php $group = $$key; @endphp
            @if($group->isNotEmpty())
                <h5>{{ $label }}</h5>
                <table>
                    <thead><tr><th>Nombre</th><th>Precio</th></tr></thead>
                    <tbody>
                        @foreach($group as $item)
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

    @if(!empty($cliente?->total))
        <div class="section">
            <h4>Total</h4>
            <p>Bs. <span class="bold">{{ number_format($cliente->total, 2) }}</span></p>
        </div>
    @endif

    @if(!empty($cliente?->nota))
        <div class="section">
            <h4>Nota Adicional</h4>
            <p>{{ $cliente->nota }}</p>
        </div>
    @endif

    @if($gestion)
        <div class="section">
            <h4>Gestión Operativa</h4>
            <ul>
                @if($gestion->guia_id)
                    <li><span class="bold">Guía:</span> {{ optional($gestion->guia)->nombre }}</li>
                @endif
                @if($gestion->traductor_id)
                    <li><span class="bold">Traductor:</span> {{ optional($gestion->traductor)->nombre }}</li>
                @endif
                @if($gestion->chofer_id)
                    <li><span class="bold">Chofer:</span> {{ optional($gestion->chofer)->nombre }}</li>
                @endif
                @if($gestion->vagoneta_id)
                    <li><span class="bold">Vagoneta:</span> {{ optional($gestion->vagoneta)->marca }}</li>
                @endif
            </ul>
        </div>
    @endif

</body>
</html>

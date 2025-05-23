<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Salida - Reserva {{ $reserva->codigo }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header-info {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .section-title {
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 5px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }
        .table th, .table td {
            padding: 4px;
            vertical-align: top;
        }
        .table th {
            background-color: #e9ecef;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

    {{-- LOGO Y ENCABEZADO --}}
    <div class="text-center mb-2">
        <img src="{{ public_path('assets/images/logo-img.png') }}" style="width: 110px;" alt="Logo">
    </div>

    <div class="header-info">
        <p>AV. CHICHAS N° 187 - TUPIZA - BOLIVIA</p>
        <p>TELF: 00591-2-6943001 / 6943003 · FAX: 00591-2-6944816</p>
        <p>Email: hola@tupizatours.com · Web: www.tupizatours.com</p>
        <p>Placa </p>
    </div>

    <h5 class="text-center mb-1">HOJA DE SALIDA Nº {{ $reserva->id }}</h5>
    <p class="text-center mb-2">TUPIZA, {{ \Carbon\Carbon::now()->format('d') }} DE {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('F')) }} DE {{ \Carbon\Carbon::now()->format('Y') }}</p>

    {{-- DATOS GENERALES --}}
    <div class="section-title">Datos de la Reserva</div>
    <table class="table table-sm table-bordered mb-2">
        <tbody>
            <tr>
                <th>Código</th>
                <td>{{ $reserva->codigo }}</td>
                <th>Tour</th>
                <td>{{ $reserva->tour->titulo }}</td>
                <th>Fecha</th>
                <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                <th>Privado</th>
                <td>{{ $reserva->tprivado ? 'Sí' : 'No' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TURISTAS RESUMIDOS --}}
    <div class="section-title">Turistas y Detalles</div>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Doc</th>
                <th>Pais</th>
                <th>Alergias</th>
                <th>Comida</th>
                <th>Tickets</th>
                <th>Habitaciones</th>
                <th>Accesorios</th>
                <th>Servicios</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resclis as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t['nombres'] }} {{ $t['apellidos'] }}</td>
                    <td>{{ $t['documento'] ?? '-' }}</td>
                    <td>{{ $t['nacionalidad'] ?? '-' }}</td>

                    {{-- Alergias --}}
                    <td>
                        @forelse($t['alergias'] ?? [] as $a)
                            {{ $a }}@if (!$loop->last), @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    {{-- Alimentación --}}
                    <td>
                        @forelse($t['alimentacion'] ?? [] as $a)
                            {{ $a }}@if (!$loop->last), @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    {{-- Tickets --}}
                    <td>
                        @forelse($t['tickets'] ?? [] as $ti)
                            {{ $ti['name'] ?? '-' }}@if (!$loop->last), @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    {{-- Habitaciones --}}
                    <td>
                        @forelse($t['habitaciones'] ?? [] as $h)
                            Día {{ $h['dia'] }} : <br> {{ $h['name'] }}@if (!$loop->last), <br> @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    {{-- Accesorios --}}
                    <td>
                        @forelse($t['accesorios'] ?? [] as $a)
                            {{ $a['name'] ?? '-' }}@if (!$loop->last), @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    {{-- Servicios --}}
                    <td>
                        @forelse($t['servicios'] ?? [] as $s)
                            {{ $s['name'] ?? '-' }}@if (!$loop->last), @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    {{-- Nota --}}
                    <td>{{ $t['nota'] ?? '-' }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- GESTIÓN OPERATIVA --}}
    @if($gestion)
        <div class="section-title mt-4">Gestión Operativa</div>
        <table class="table table-sm table-bordered">
            <tbody>
                @if($gestion->guia_id)
                    <tr>
                        <th>Guía</th>
                        <td>{{ optional($gestion->guia)->nombre }}</td>
                    </tr>
                @endif

                @if($gestion->traductor_id)
                    <tr>
                        <th>Traductor</th>
                        <td>{{ optional($gestion->traductor)->nombre }}</td>
                    </tr>
                @endif

                @if($gestion->chofer_id)
                    <tr>
                        <th>Chofer</th>
                        <td>
                            {{ optional($gestion->chofer)->nombre }}
                            @if(optional($gestion->chofer)->licencia)
                                <br><span class="text-muted">Licencia: {{ $gestion->chofer->licencia }}</span>
                            @endif
                        </td>
                    </tr>
                @endif

                @if($gestion->vagoneta_id)
                    <tr>
                        <th>Vagoneta</th>
                        <td>
                            {{ optional($gestion->vagoneta)->marca }}
                            @if(optional($gestion->vagoneta)->patente)
                                <br><span class="text-muted">Patente: {{ $gestion->vagoneta->patente }}</span>
                            @endif
                            @if(optional($gestion->provag)->nombre)
                                <br><span class="text-muted">Prestatario: {{ $gestion->provag->nombre }} {{ $gestion->provag->apellido }}</span>
                            @endif
                        </td>
                    </tr>
                @endif

                @if($gestion->caballo_id)
                    <tr>
                        <th>Caballo</th>
                        <td>
                            {{ optional($gestion->caballo)->nombre }}
                            @if(optional($gestion->procab)->nombre)
                                <br><span class="text-muted">Prestatario: {{ $gestion->procab->nombre }} {{ $gestion->procab->apellido }}</span>
                            @endif
                        </td>
                    </tr>
                @endif

                @if($gestion->bicicleta_id)
                    <tr>
                        <th>Bicicleta</th>
                        <td>
                            {{ optional($gestion->bicicleta)->nombre }}
                            @if(optional($gestion->probic)->nombre)
                                <br><span class="text-muted">Prestatario: {{ $gestion->probic->nombre }} {{ $gestion->probic->apellido }}</span>
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif
</body>
</html>

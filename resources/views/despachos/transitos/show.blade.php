@extends('layouts.app')

@section('template_title')
    Ver en tránsito
@endsection

@section('estilos')
    <style>
        h4.title_dir { font-size: 18px; font-weight: 700; color: rgb(63, 66, 87); }
        .bg-moradito { border: 1px dashed rgb(98, 95, 241); }
        .card-infos, dt { text-transform: uppercase; }
        .text-right { text-align: right; }
        .form_cantidad { max-width: 60px; }
        .input-spinner .btn-white { width: 40px; }
        .form_tran .form-control { pointer-events: none; }
    </style>
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 title_page">Resumen de Turistas</h6>

                @if($gestion)
                    @php
                        $pdfPath = public_path("despachos/transito_{$reserva->codigo}.pdf");
                        $pdfExists = file_exists($pdfPath);
                    @endphp

                    <div class="d-flex gap-2">
                        <form action="{{ route('desfin.store') }}" method="POST" class="mr-2">
                            @csrf
                            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
                            <button type="submit" class="btn btn-success btn-sm">Finalizar</button>
                        </form>

                        @if($pdfExists)
                            <a href="{{ asset("public/despachos/transito_{$reserva->codigo}.pdf") }}" target="_blank" class="btn btn-primary btn-sm">
                                Ver PDF
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Doc</th>
                            <th>País</th>
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
                                <td>{{ $t->nombres }} {{ $t->apellidos }}</td>
                                <td>{{ $t->documento ?? '-' }}</td>
                                <td>{{ $t->nacionalidad ?? '-' }}</td>

                                <td>
                                    @forelse($t->alergias ?? [] as $a)
                                        {{ $a }}@if (!$loop->last), @endif
                                    @empty - @endforelse
                                </td>

                                <td>
                                    @forelse($t->alimentacion ?? [] as $a)
                                        {{ $a }}@if (!$loop->last), @endif
                                    @empty - @endforelse
                                </td>

                                <td>
                                    @forelse($t->tickets ?? [] as $ti)
                                        {{ $ti['name'] ?? '-' }}@if (!$loop->last), @endif
                                    @empty - @endforelse
                                </td>

                                <td>
                                    @forelse($t->habitaciones ?? [] as $h)
                                        Día {{ $h['dia'] }}: {{ $h['name'] }}<br>
                                    @empty - @endforelse
                                </td>

                                <td>
                                    @forelse($t->accesorios ?? [] as $a)
                                        {{ $a['name'] ?? '-' }}@if (!$loop->last), @endif
                                    @empty - @endforelse
                                </td>

                                <td>
                                    @forelse($t->servicios ?? [] as $s)
                                        {{ $s['name'] ?? '-' }}@if (!$loop->last), @endif
                                    @empty - @endforelse
                                </td>

                                <td>{{ $t->nota ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Gestión Operativa --}}
            @if($gestion)
                <h6 class="mt-4 mb-2">Gestión Operativa</h6>
                <table class="table table-sm table-bordered">
                    <tbody>
                        @if($gestion->guia_id)
                            <tr><th>Guía</th><td>{{ optional($gestion->guia)->nombre }}</td></tr>
                        @endif
                        @if($gestion->traductor_id)
                            <tr><th>Traductor</th><td>{{ optional($gestion->traductor)->nombre }}</td></tr>
                        @endif
                        @if($gestion->chofer_id)
                            <tr>
                                <th>Chofer</th>
                                <td>
                                    {{ optional($gestion->chofer)->nombre }}
                                    @if(optional($gestion->chofer)->licencia)
                                        <br><small class="text-muted">Licencia: {{ $gestion->chofer->licencia }}</small>
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
                                        <br><small class="text-muted">Patente: {{ $gestion->vagoneta->patente }}</small>
                                    @endif
                                    @if(optional($gestion->provag)->nombre)
                                        <br><small class="text-muted">Prestatario: {{ $gestion->provag->nombre }} {{ $gestion->provag->apellido }}</small>
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
                                        <br><small class="text-muted">Prestatario: {{ $gestion->procab->nombre }} {{ $gestion->procab->apellido }}</small>
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
                                        <br><small class="text-muted">Prestatario: {{ $gestion->probic->nombre }} {{ $gestion->probic->apellido }}</small>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection


@section('footer_scripts')
    
@endsection
@extends('layouts.app')

@section('template_title')
    Detalle de Caja
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detalle de Caja #{{ $caja->id }}</h5>
        </div>

        <div class="card-body">
            <p><strong>Usuario:</strong> {{ $caja->user->name ?? '-' }}</p>
            <p><strong>Fecha de apertura:</strong> {{ \Carbon\Carbon::parse($caja->apertura)->format('d/m/Y H:i') }}</p>
            <p><strong>Fecha de cierre:</strong> {{ $caja->cierre ? \Carbon\Carbon::parse($caja->cierre)->format('d/m/Y H:i') : '—' }}</p>
            <p><strong>Monto inicial:</strong> Bs. {{ number_format($caja->monto_inicial, 2, '.', ',') }}</p>
            <p><strong>Monto final:</strong> Bs. {{ number_format($caja->monto_final ?? 0, 2, '.', ',') }}</p>

            <hr>

            <h6>Movimientos</h6>
            @if($caja->movimientos->isEmpty())
                <p class="text-muted">No hay movimientos registrados.</p>
            @else
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Cuenta</th>
                            <th>Monto</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($caja->movimientos as $i => $m)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($m->tipo) }}</td>
                                <td>{{ $m->cuenta->nombre ?? '-' }}</td>
                                <td>Bs. {{ number_format($m->monto, 2, '.', ',') }}</td>
                                <td>{{ $m->descripcion }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <a href="{{ route('caja.index') }}" class="btn btn-outline-secondary mt-3">← Volver</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('template_title')
    Estado de la Caja
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Estado de la Caja</h5>
                </div>

                <div class="card-body">
                    {{-- Mensajes flash --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(isset($cajaAbierta) && $cajaAbierta)
                        {{-- Caja abierta --}}
                        <div class="mb-3">
                            <h6 class="text-success">Caja actualmente abierta</h6>
                            <p><strong>Usuario:</strong> {{ $cajaAbierta->user->name ?? '-' }}</p>
                            <p><strong>Fecha de apertura:</strong> {{ \Carbon\Carbon::parse($cajaAbierta->apertura)->format('d/m/Y H:i') }}</p>
                            <p><strong>Monto inicial:</strong> Bs. {{ number_format($cajaAbierta->monto_inicial, 2, '.', ',') }}</p>
                        </div>

                        <hr>

                        <p><strong>Total ingresos:</strong> Bs. {{ number_format($ingresos ?? 0, 2, '.', ',') }}</p>
                        <p><strong>Total egresos:</strong> Bs. {{ number_format($egresos ?? 0, 2, '.', ',') }}</p>
                        <p>
                            <strong>Saldo actual:</strong>
                            <span class="text-primary">Bs. {{ number_format($saldoActual ?? 0, 2, '.', ',') }}</span>
                        </p>

                        <form action="{{ route('caja.cerrar') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-block">Cerrar Caja</button>
                        </form>

                        <a href="{{ route('caja.movimientos') }}" class="btn btn-outline-secondary btn-block mt-2">
                            Ver Detalles / Movimientos
                        </a>

                    @else
                        {{-- Sin caja activa --}}
                        <h6 class="text-danger">No hay caja abierta actualmente</h6>
                        <form action="{{ route('caja.abrir') }}" method="POST" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label for="monto_inicial">Monto Inicial (Bs)</label>
                                <input type="number" name="monto_inicial" id="monto_inicial" step="0.01" min="0" required class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success btn-block mt-2">Abrir Caja</button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

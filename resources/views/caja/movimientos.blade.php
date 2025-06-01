@extends('layouts.app')

@section('template_title')
    Movimientos de Caja
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Movimientos de Caja</h4>
        @if($caja)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMovimiento">
                + Registrar Movimiento
            </button>
        @endif
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($caja)
        {{-- Filtro tipo --}}
        <form method="GET" class="form-inline mb-3">
            <label class="mr-2">Filtrar por tipo:</label>
            <select name="tipo" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="ingreso" {{ request('tipo') === 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                <option value="egreso" {{ request('tipo') === 'egreso' ? 'selected' : '' }}>Egreso</option>
            </select>
        </form>

        {{-- Tabla de movimientos --}}
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="thead-light">
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
                    @forelse($movimientos as $i => $m)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i') }}</td>
                            @php
                                $tipo = strtolower(trim($m->tipo ?? ''));
                            @endphp                            
                            <td>
                                <span class="badge-{{ $tipo === 'ingreso' ? 'success' : 'danger' }} text-dark">
                                    {{ ucfirst($tipo) }}
                                </span>
                            </td>
                            <td>{{ $m->cuenta->nombre ?? '-' }}</td>
                            <td>Bs. {{ number_format($m->monto, 2, '.', ',') }}</td>
                            <td>{{ $m->descripcion }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay movimientos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $movimientos->appends(['tipo' => request('tipo')])->links() }}
    @else
        <div class="alert alert-warning">No hay una caja abierta. Dirígete a <a href="{{ route('caja.index') }}">Apertura de Caja</a>.</div>
    @endif
</div>

@if($caja)
    @include('caja.partials.modal_movimiento', ['cuentas' => $cuentas])
@endif
@endsection

@section('footer_scripts')
<script>
    $(document).ready(function(){
        $('#modalMovimiento').on('show.bs.modal', function () {
            console.log('Modal abierto');
        });
    });
</script>
@endsection

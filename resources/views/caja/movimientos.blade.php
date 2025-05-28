@extends('layouts.app')

@section('template_title')
    Movimientos de Caja
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Movimientos de Caja</h4>

        @if($caja)
            <button class="btn btn-success" data-toggle="modal" data-target="#modalMovimiento">
                + Nuevo Movimiento
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
                <thead class="thead-light text-center">
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
                            <td>
                                <span class="badge badge-{{ $m->tipo === 'ingreso' ? 'success' : 'danger' }}">
                                    {{ ucfirst($m->tipo) }}
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

{{-- MODAL CREAR MOVIMIENTO --}}
@if($caja)
<div class="modal fade" id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="modalMovimientoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('caja.ingreso') }}"> {{-- Este será reemplazado dinámicamente --}}
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Movimiento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cuenta_id">Cuenta</label>
                <select name="cuenta_id" id="cuenta_id" class="form-control" required>
                    @foreach(\App\Models\Caja\CuentaCaja::all() as $cuenta)
                        <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="monto">Monto (Bs)</label>
                <input type="number" name="monto" step="0.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="2" required></textarea>
            </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif
@endsection

@props([
    'reserva',
    'gestion',
    'propietarios' => [],
])

<div class="card mt-4">
    <div class="card-body">
        <form action="{{ route('cajacobros.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="pagina" value="gestions">
            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
            <input type="hidden" name="tour_id" value="{{ $reserva->tour_id }}">

            {{-- Tipo de operación --}}
            <div class="form-group mb-3">
                <label for="tipo_operacion"><strong>Tipo de operación</strong></label>
                <select class="form-control" id="tipo_operacion" name="tipo_operacion" required>
                    <option value="">Seleccionar</option>
                    <option value="anticipo">Anticipo</option>
                    <option value="pago">Pago de Servicio</option>
                </select>
            </div>

            {{-- Prestatario --}}
            <div class="form-group mb-3">
                <label for="prestatario"><strong>Prestatario</strong></label>
                <select class="form-control" id="prestatario" name="prestatario" required>
                    <option value="">Seleccionar</option>
                    @foreach($propietarios as $prop)
                        <option value="{{ $prop->id }}"
                            @selected(
                                $gestion->provag_id == $prop->id ||
                                $gestion->procab_id == $prop->id ||
                                $gestion->probic_id == $prop->id
                            )>
                            {{ $prop->nombre }} {{ $prop->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Subtotal --}}
            <div class="form-group mb-3">
                <label for="subtotal"><strong>Monto del Servicio</strong></label>
                <input type="number" class="form-control" id="subtotal" name="subtotal"
                       value="{{ $gestion->vagoneta_t ?? $gestion->caballo_t ?? $gestion->bicicleta_t ?? 0 }}"
                       readonly>
            </div>

            {{-- Anticipo actual --}}
            <div class="form-group mb-3">
                <label for="anticipoActual"><strong>Anticipo</strong></label>
                <input type="number" class="form-control" id="anticipoActual" name="anticipoActual" value="0">
            </div>

            {{-- Saldo --}}
            <div class="form-group mb-4">
                <label for="saldo"><strong>Saldo restante</strong></label>
                <input type="number" class="form-control" id="saldo" name="saldo" value="0" readonly>
            </div>

            {{-- IDs ocultos --}}
            <input type="hidden" id="dserv" name="dserv" value="">
            <input type="hidden" id="dserid" name="dserid" value="">

            <button type="submit" class="btn btn-primary col-md-12 text-uppercase">
                Realizar operación
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const anticipoInput = document.getElementById('anticipoActual');
        const subtotalInput = document.getElementById('subtotal');
        const saldoInput = document.getElementById('saldo');

        function updateSaldo() {
            const anticipo = parseFloat(anticipoInput.value) || 0;
            const subtotal = parseFloat(subtotalInput.value) || 0;
            const saldo = subtotal - anticipo;
            saldoInput.value = saldo.toFixed(2);
        }

        anticipoInput.addEventListener('input', updateSaldo);
        updateSaldo();
    });
</script>
@endpush

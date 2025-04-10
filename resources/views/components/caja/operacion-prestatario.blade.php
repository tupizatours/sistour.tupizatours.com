@props([
    'reserva',
    'gestion',
    'propietarios' => [],
])

<div class="card mt-4">
    <div class="card-body">
        <form action="{{ route('cajacobros.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Campos ocultos base --}}
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

            {{-- Tipo de servicio --}}
            <div class="form-group mb-3">
                <label for="tipo_servicio"><strong>Servicio a pagar</strong></label>
                <select class="form-control" id="tipo_servicio" required>
                    <option value="">Seleccionar</option>
                    @if($gestion->provag_id)
                        <option value="vagoneta"
                                data-id="{{ $gestion->vagoneta_id }}"
                                data-pres="{{ $gestion->provag_id }}"
                                data-costo="{{ $gestion->vagoneta_t }}">
                            Vagoneta
                        </option>
                    @endif
                    @if($gestion->procab_id)
                        <option value="caballo"
                                data-id="{{ $gestion->caballo_id }}"
                                data-pres="{{ $gestion->procab_id }}"
                                data-costo="{{ $gestion->caballo_t }}">
                            Caballo
                        </option>
                    @endif
                    @if($gestion->probic_id)
                        <option value="bicicleta"
                                data-id="{{ $gestion->bicicleta_id }}"
                                data-pres="{{ $gestion->probic_id }}"
                                data-costo="{{ $gestion->bicicleta_t }}">
                            Bicicleta
                        </option>
                    @endif
                </select>
            </div>

            {{-- Prestatario --}}
            <div class="form-group mb-3">
                <label for="prestatario"><strong>Prestatario</strong></label>
                <select class="form-control" id="prestatario" name="prestatario" required>
                    <option value="">Seleccionar</option>
                    @foreach($propietarios as $prop)
                        <option value="{{ $prop->id }}">{{ $prop->nombre }} {{ $prop->apellido }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Subtotal --}}
            <div class="form-group mb-3">
                <label for="subtotal"><strong>Monto del Servicio</strong></label>
                <input type="number" class="form-control" id="subtotal" name="subtotal" value="0" readonly>
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

            {{-- Campos ocultos necesarios --}}
            <input type="hidden" id="dserv" name="dserv">
            <input type="hidden" id="dserid" name="dserid">

            <button type="submit" class="btn btn-primary col-md-12 text-uppercase">
                Realizar operación
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoServicio = document.getElementById('tipo_servicio');
        const subtotalInput = document.getElementById('subtotal');
        const prestatarioSelect = document.getElementById('prestatario');
        const anticipoInput = document.getElementById('anticipoActual');
        const saldoInput = document.getElementById('saldo');
        const dservInput = document.getElementById('dserv');
        const dseridInput = document.getElementById('dserid');

        function updateServicio() {
            const option = tipoServicio.options[tipoServicio.selectedIndex];
            const costo = parseFloat(option.dataset.costo || 0);
            const prestatarioId = option.dataset.pres || '';
            const recursoId = option.dataset.id || '';
            const tipo = option.value;

            subtotalInput.value = costo;
            prestatarioSelect.value = prestatarioId;
            dservInput.value = tipo;
            dseridInput.value = recursoId;

            updateSaldo();
        }

        function updateSaldo() {
            const anticipo = parseFloat(anticipoInput.value || 0);
            const subtotal = parseFloat(subtotalInput.value || 0);
            const saldo = subtotal - anticipo;
            saldoInput.value = saldo.toFixed(2);
        }

        tipoServicio.addEventListener('change', updateServicio);
        anticipoInput.addEventListener('input', updateSaldo);

        // Preinicializar
        updateServicio();
    });
</script>
@endpush

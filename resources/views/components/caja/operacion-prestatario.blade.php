@props([
    'reserva',
    'gestion',
    'propietarios' => [],
])

<div class="card mt-4">
    <div class="card-body">
        <form action="{{ route('cajacobros.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Base --}}
            <input type="hidden" name="pagina" value="gestions">
            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
            <input type="hidden" name="tour_id" value="{{ $reserva->tour_id }}">

            {{-- Activar anticipo --}}
            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="toggleAnticipo">
                    <label class="form-check-label" for="toggleAnticipo"><strong>¿Es un anticipo?</strong></label>
                </div>
            </div>

            {{-- Prestatario (solo visible si es anticipo) / no esta visible  --}}
            <div class="form-group mb-3 d-none" id="prestatarioWrapper">
                <label for="prestatario"><strong>Prestatario</strong></label>
                <select class="form-control" id="prestatario" name="prestatario">
                    <option value="">Seleccionar</option>
                    @foreach($propietarios as $prop)
                        <option value="{{ $prop->id }}">{{ $prop->nombre }} {{ $prop->apellido }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tipo de servicio relacionado / es visible y deben aparecer los servicios realizados a la gestion --}}
            <div class="form-group mb-3">
                <label for="tipo_servicio"><strong>Servicio a pagar</strong></label>
                <select class="form-control" id="tipo_servicio" required>
                    <option value="">Seleccionar</option>
                    @if($gestion->provag_id)
                        <option value="vagoneta" data-id="{{ $gestion->vagoneta_id }}" data-pres="{{ $gestion->provag_id }}" data-costo="{{ $gestion->vagoneta_t }}">Vagoneta</option>
                    @endif
                    @if($gestion->procab_id)
                        <option value="caballo" data-id="{{ $gestion->caballo_id }}" data-pres="{{ $gestion->procab_id }}" data-costo="{{ $gestion->caballo_t }}">Caballo</option>
                    @endif
                    @if($gestion->probic_id)
                        <option value="bicicleta" data-id="{{ $gestion->bicicleta_id }}" data-pres="{{ $gestion->probic_id }}" data-costo="{{ $gestion->bicicleta_t }}">Bicicleta</option>
                    @endif
                </select>
            </div>

            {{-- Monto del servicio --}}
            <div class="form-group mb-3">
                <label for="subtotal"><strong>Monto del Servicio</strong></label>
                <input type="number" class="form-control" id="subtotal" name="subtotal" value="0" readonly>
            </div>

            {{-- Anticipo deberia activarse si el checkbox esta activo--}}
            <div class="form-group mb-3">
                <label for="anticipoActual"><strong>Anticipo</strong></label>
                <input type="number" class="form-control" id="anticipoActual" name="anticipoActual" value="0">
            </div>

            {{-- Total no est agreando el monto   --}}
            <div class="form-group mb-4">
                <label for="total"><strong>Total</strong></label>
                <input type="number" class="form-control" id="total" name="total" value="0" readonly>
            </div>

            {{-- Identificadores ocultos --}}
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
        const prestatarioWrapper = document.getElementById('prestatarioWrapper');
        const anticipoInput = document.getElementById('anticipoActual');
        const totalInput = document.getElementById('total');
        const dservInput = document.getElementById('dserv');
        const dseridInput = document.getElementById('dserid');
        const toggleAnticipo = document.getElementById('toggleAnticipo');

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

            updateTotal();
        }

        function updateTotal() {
            const anticipo = parseFloat(anticipoInput.value || 0);
            const subtotal = parseFloat(subtotalInput.value || 0);
            let total = subtotal - anticipo;

            if (total < 0) total = 0; // Previene totales negativos
            totalInput.value = total.toFixed(2);
        }

        // Mostrar/Ocultar prestatario si es anticipo
        toggleAnticipo.addEventListener('change', function () {
                    prestatarioWrapper.classList.toggle('d-none', !this.checked);
                });

                tipoServicio.addEventListener('change', updateServicio);
                anticipoInput.addEventListener('input', updateTotal);

                toggleAnticipo.addEventListener('change', function () {
            const isChecked = this.checked;
            prestatarioWrapper.classList.toggle('d-none', !isChecked);
            anticipoInput.disabled = !isChecked;

            if (!isChecked) {
                anticipoInput.value = 0;
                updateTotal();
            }
        });

        updateServicio(); // inicial
    });
</script>
@endpush

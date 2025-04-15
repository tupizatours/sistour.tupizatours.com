@props([
    'reserva',
    'gestion',
    'propietarios' => [],
])

<div class="card mt-4">
    <div class="card-body">
        <form action="{{ route('cajacobros.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Datos base --}}
            <input type="hidden" name="pagina" value="gestions">
            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
            <input type="hidden" name="tour_id" value="{{ $reserva->tour_id }}">

            {{-- Activar Anticipo --}}
            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="toggleAnticipo">
                    <label class="form-check-label" for="toggleAnticipo"><strong>Activar Anticipo</strong></label>
                </div>
            </div>

            {{-- Prestatario SIEMPRE visible y obligatorio --}}
            <div class="form-group mb-3" id="prestatarioWrapper">
                <label for="prestatario"><strong>Prestatario</strong></label>
                <select class="form-control" id="prestatario" name="prestatario" required>
                    <option value="">Seleccionar</option>
                    @foreach($propietarios as $prop)
                        <option value="{{ $prop->id }}">{{ $prop->nombre }} {{ $prop->apellido }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Servicio relacionado --}}
            <div class="form-group mb-3">
                <label for="tipo_servicio"><strong>Servicio a pagar</strong></label>
                <select class="form-control" id="tipo_servicio" required>
                    <option value="">Seleccionar</option>
                    @if($gestion->provag_id)
                        <option value="vagoneta" data-pres="{{ $gestion->provag_id }}" data-id="{{ $gestion->vagoneta_id }}" data-costo="{{ $gestion->vagoneta_t }}">Vagoneta</option>
                    @endif
                    @if($gestion->procab_id)
                        <option value="caballo" data-pres="{{ $gestion->procab_id }}" data-id="{{ $gestion->caballo_id }}" data-costo="{{ $gestion->caballo_t }}">Caballo</option>
                    @endif
                    @if($gestion->probic_id)
                        <option value="bicicleta" data-pres="{{ $gestion->probic_id }}" data-id="{{ $gestion->bicicleta_id }}" data-costo="{{ $gestion->bicicleta_t }}">Bicicleta</option>
                    @endif
                </select>
            </div>

            {{-- Monto del Servicio --}}
            <div class="form-group mb-3">
                <label for="subtotal"><strong>Monto del Servicio</strong></label>
                <input type="number" class="form-control" id="subtotal" name="subtotal" value="0" readonly>
            </div>

            {{-- Anticipo --}}
            <div class="form-group mb-3">
                <label for="anticipoActual"><strong>Anticipo</strong></label>
                <input type="number" class="form-control" id="anticipoActual" name="anticipoActual" value="0" disabled>
            </div>

            {{-- Alerta de saldo (inicialmente oculta) --}}
            <div class="alert alert-danger d-none" id="alerta-validacion"></div>

            {{-- Total a pagar --}}
            <div class="form-group mb-4">
                <label for="total"><strong>Total</strong></label>
                <input type="number" class="form-control" id="total" name="total" value="0" readonly>
            </div>

            {{-- Datos ocultos para el pago --}}
            <input type="hidden" id="dserv" name="dserv">
            <input type="hidden" id="dserid" name="dserid">

            <button type="submit" class="btn btn-primary col-md-12 text-uppercase">Realizar operación</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleAnticipo = document.getElementById('toggleAnticipo');
        const prestatarioSelect = document.getElementById('prestatario');
        const tipoServicioSelect = document.getElementById('tipo_servicio');
        const subtotalInput = document.getElementById('subtotal');
        const anticipoInput = document.getElementById('anticipoActual');
        const totalInput = document.getElementById('total');
        const alerta = document.getElementById('alerta-validacion');
        const dservInput = document.getElementById('dserv');
        const dseridInput = document.getElementById('dserid');

        let montoServicio = 0;

        window.addEventListener('totalidadUpdated', function (e) {
            montoServicio = parseFloat(e.detail.total || 0);
            subtotalInput.value = montoServicio.toFixed(2);
            updateTotal();
        });

        function updateServicio() {
            const option = tipoServicioSelect.options[tipoServicioSelect.selectedIndex];
            dservInput.value = option.value;
            dseridInput.value = option.dataset.id || '';

            if (toggleAnticipo.checked && option.dataset.pres) {
                prestatarioSelect.value = option.dataset.pres;
            }

            updateTotal();
        }

        function updateTotal() {
            const subtotal = parseFloat(subtotalInput.value || 0);
            const anticipo = toggleAnticipo.checked ? parseFloat(anticipoInput.value || 0) : 0;
            const total = subtotal + anticipo;
            totalInput.value = total.toFixed(2);

            validarContraCostoPorpago(total);
            if (toggleAnticipo.checked) {
                validarContraSaldoAnticipo(anticipo);
            } else {
                ocultarAlerta();
            }
        }

        function validarContraCostoPorpago(total) {
            const tipo = tipoServicioSelect.value;
            const servicioId = prestatarioSelect.value;

            if (!tipo || !servicioId) return;

            fetch(`/api/validar-monto-servicio?reserva_id={{ $reserva->id }}&tipo_servicio=${tipo}&servicio_id=${servicioId}`)
                .then(res => res.json())
                .then(data => {
                    if (total > data.saldo_disponible) {
                        mostrarAlerta(`⚠️ El total excede el saldo permitido por el servicio: Bs. ${data.saldo_disponible.toFixed(2)}`);
                    } else {
                        ocultarAlerta();
                    }
                })
                .catch(err => console.warn('Error validando contra porpago:', err));
        }

        function validarContraSaldoAnticipo(anticipo) {
            const prestatarioId = prestatarioSelect.value;
            if (!prestatarioId) return;

            fetch(`/api/saldo-anticipo?reserva_id={{ $reserva->id }}&prestatario_id=${prestatarioId}`)
                .then(res => res.json())
                .then(data => {
                    if (anticipo > data.saldo_disponible) {
                        mostrarAlerta(`⚠️ El anticipo excede el saldo disponible del prestatario: Bs. ${data.saldo_disponible.toFixed(2)}`);
                    } else {
                        ocultarAlerta();
                    }
                })
                .catch(err => console.warn('Error validando anticipo:', err));
        }

        function mostrarAlerta(mensaje) {
            alerta.textContent = mensaje;
            alerta.classList.remove('d-none');
        }

        function ocultarAlerta() {
            alerta.classList.add('d-none');
            alerta.textContent = '';
        }

        toggleAnticipo.addEventListener('change', function () {
            anticipoInput.disabled = !this.checked;
            if (!this.checked) anticipoInput.value = 0;
            updateTotal();
        });

        tipoServicioSelect.addEventListener('change', updateServicio);
        prestatarioSelect.addEventListener('change', updateTotal);
        anticipoInput.addEventListener('input', updateTotal);

        updateServicio(); // Init
    });
</script>
@endpush

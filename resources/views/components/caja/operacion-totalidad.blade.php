@props([
    'totalGeneralHoteles' => 0,
    'totalGeneralTickets' => 0,
    'totalGeneralAccesorios' => 0,
    'totalGeneralServicios' => 0,
    'totalGeneralGasto' => 0,
    'prestatarios' => [],
    'tiposTotalidadesPagadas' => [],
])

<div class="card">
    <div class="card-header bg-transparent pt-3 pb-3">
        <h6 class="mb-0 title_page">PAGO DE TOTALIDAD DE SERVICIOS</h6>
    </div>

    <div class="card-body">
        @foreach([
            'Hoteles' => $totalGeneralHoteles,
            'Tickets' => $totalGeneralTickets,
            'Accesorios' => $totalGeneralAccesorios,
            'Servicios' => $totalGeneralServicios,
        ] as $label => $monto)
            <dl class="col-md-12 row">
                <dt class="col-sm-9">
                    <div class="form-check"> 
                        <input type="hidden" name="checkboxes[{{ strtolower($label) }}][nombre]" value="{{ $label }}">
                        <input type="hidden" name="checkboxes[{{ strtolower($label) }}][monto]" value="{{ $monto }}">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            data-nombre="{{ $label }}"
                            data-monto="{{ $monto }}"
                            id="check_{{ strtolower($label) }}"
                            name="checkboxes[{{ strtolower($label) }}][selected]"
                            {{ in_array(strtolower($label), $tiposTotalidadesPagadas ?? []) ? 'disabled' : '' }} > 

                            @if (in_array(strtolower($label), $tiposTotalidadesPagadas ?? []))
                                <small class="text-muted">(ya fue pagado)</small>
                            @endif

                        <label class="form-check-label" for="check_{{ strtolower($label) }}">{{ $label }}</label>
                    </div>
                </dt>
                <dd class="col-sm-3 text-right">Bs. {{ number_format($monto, 2) }}</dd>
            </dl>
        @endforeach

        <form id="form-totalidades-sombra" style="display:none;">
            @foreach([
                'Hoteles' => $totalGeneralHoteles,
                'Tickets' => $totalGeneralTickets,
                'Accesorios' => $totalGeneralAccesorios,
                'Servicios' => $totalGeneralServicios,
            ] as $label => $monto)
                <input type="hidden" name="totalidades_temp[{{ strtolower($label) }}][nombre]" value="{{ strtolower($label) }}">
                <input type="hidden" name="totalidades_temp[{{ strtolower($label) }}][monto]" value="{{ $monto }}">
                <input type="checkbox" id="sombra_{{ strtolower($label) }}"
                       name="totalidades_temp[{{ strtolower($label) }}][selected]"
                       value="1" style="display:none;">
            @endforeach
        </form>
    </div>

    <div class="card-footer">
        <dl class="col-md-12 row mb-0">
            <dt class="col-sm-9">
                <label class="form-label">Totalidad Seleccionada</label>
            </dt>
            <dd class="col-sm-3 text-right">
                <span id="totalidadSeleccionada">Bs. 0.00</span>
            </dd>
        </dl>
    </div>
</div>
@push('scripts')
<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input.form-check-input[data-monto]');
            const totalDisplay = document.getElementById('totalidadSeleccionada');

            if (!checkboxes.length || !totalDisplay) {
                console.warn("⚠️ Checkboxes o totalDisplay no encontrados.");
                return;
            }

            const formatBs = (amount) => `Bs. ${parseFloat(amount).toFixed(2)}`;

            const calcularTotalidad = () => {
                let total = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.monto || 0);
                    }
                });

                totalDisplay.textContent = formatBs(total);

                const event = new CustomEvent('totalidadUpdated', {
                    detail: { total },
                    bubbles: true,
                    cancelable: true,
                });

                window.dispatchEvent(event);
                console.log("✅ Event 'totalidadUpdated' dispatched with total:", total);
            };

            // Asegurar que los eventos estén correctamente ligados
            checkboxes.forEach(cb => {
                cb.addEventListener('change', calcularTotalidad);
            });

            // Ejecutar cálculo inicial
            calcularTotalidad();
        });

        // Debug: escucha global (puedes mover esto al otro componente para verificar recepción)
        window.addEventListener('totalidadUpdated', function (e) {
            console.log("📡 Event received in listener → Totalidad:", e.detail.total);
        });
    })();
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const visibles = document.querySelectorAll('input.form-check-input[data-monto]');
        const sombraForm = document.getElementById('form-totalidades-sombra');

        visibles.forEach(visible => {
            const tipo = visible.getAttribute('data-nombre').toLowerCase();
            const sombra = sombraForm.querySelector(`#sombra_${tipo}`);

            if (sombra) {
                visible.addEventListener('change', () => {
                    sombra.checked = visible.checked;
                });
            }
        });
    });

</script>
@endpush


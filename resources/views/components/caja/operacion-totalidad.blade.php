@props([
    'totalGeneralHoteles' => 0,
    'totalGeneralTickets' => 0,
    'totalGeneralAccesorios' => 0,
    'totalGeneralServicios' => 0,
    'totalGeneralGasto' => 0,
    'prestatarios' => [],
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
                        <input class="form-check-input"
                               type="checkbox"
                               data-nombre="{{ $label }}"
                               data-monto="{{ $monto }}"
                               id="check_{{ strtolower($label) }}"
                               name="checkboxes[{{ strtolower($label) }}][selected]">
                        <label class="form-check-label" for="check_{{ strtolower($label) }}">{{ $label }}</label>
                    </div>
                </dt>
                <dd class="col-sm-3 text-right">Bs. {{ number_format($monto, 2) }}</dd>
            </dl>
        @endforeach
    </div>

    <div class="card-footer">
        <dl class="col-md-12 row mb-0">
            <dt class="col-sm-9">
                <label class="form-label">Totalidad de Gastos</label>
            </dt>
            <dd class="col-sm-3 text-right">Bs. {{ number_format($totalGeneralGasto, 2) }}</dd>
        </dl>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input.form-check-input[data-monto]');

        function dispatchTotalidad() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.monto || 0);
                }
            });

            window.dispatchEvent(new CustomEvent('totalidadUpdated', {
                detail: { total }
            }));
        }

        checkboxes.forEach(cb => cb.addEventListener('change', dispatchTotalidad));
        dispatchTotalidad(); // Emitir al cargar
    });
</script>
@endpush

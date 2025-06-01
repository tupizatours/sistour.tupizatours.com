<div class="modal fade" id="modalMovimiento" tabindex="-1" aria-labelledby="modalMovimientoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('caja.movimiento') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Tipo</label>
                        <select name="tipo" id="selectTipo" class="form-control" required>
                            <option value="">Seleccione tipo</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="grupoCuenta" style="display: none;">
                        <label>Cuenta contable</label>
                        <select name="cuenta_caja_id" id="selectCuenta" class="form-control" required>
                            <option value="">Seleccione cuenta</option>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="grupoPrestatario" style="display: none;">
                        <label id="labelPrestatario">Prestatario</label>
                        <select name="origen_id" id="selectPrestatario" class="form-control"></select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Monto</label>
                        <input type="number" name="monto" step="0.01" min="0" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Descripción (opcional)</label>
                        <input type="text" name="descripcion" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnGuardar" disabled>Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const cuentasContables = @json($cuentas);

    function cargarCuentas(tipo) {
        const $cuenta = $('#selectCuenta');
        $cuenta.empty().append('<option value="">Seleccione cuenta</option>');

        const cuentasFiltradas = cuentasContables.filter(c => c.tipo === tipo);
        cuentasFiltradas.forEach(c => {
            $cuenta.append(`<option value="${c.id}" data-nombre="${c.nombre}">${c.nombre}</option>`);
        });

        $('#grupoCuenta').toggle(cuentasFiltradas.length > 0);
    }

    function cargarPrestatariosDesdeCuenta(nombreCuenta) {
        const nombre = nombreCuenta.toLowerCase();

        if (nombre.includes('con bienes')) {
            cargarPrestatarios('con_bienes');
            $('#labelPrestatario').text('Prestatario (con bienes)');
        } else if (nombre === 'pago a prestatarios') {
            cargarPrestatarios('sin_bienes');
            $('#labelPrestatario').text('Prestatario (sin bienes)');
        } else if (nombre.includes('anticipo')) {
            cargarPrestatarios('todos');
            $('#labelPrestatario').text('Prestatario (todos)');
        } else {
            $('#grupoPrestatario').hide();
            $('#selectPrestatario').empty();
        }
    }



    function cargarPrestatarios(subtipo) {
        $.get('/api/porpagos-disponibles?subtipo=' + subtipo, function (data) {
            let options = '<option value="">Seleccionar</option>';
            data.forEach(function (item) {
                options += `<option value="${item.id}">${item.nombre} (Bs. ${item.monto})</option>`;
            });
            $('#selectPrestatario').html(options);
            $('#grupoPrestatario').show();
        });
    }

    function validarFormulario() {
        const monto = parseFloat($('input[name="monto"]').val() || 0);
        const cuenta = $('#selectCuenta').val();
        const prestatarioVisible = $('#grupoPrestatario').is(':visible');
        const prestatario = $('#selectPrestatario').val();

        const valido = monto > 0 && cuenta && (!prestatarioVisible || prestatario);
        $('#btnGuardar').prop('disabled', !valido);
    }

    $(document).ready(function () {
        $('#grupoCuenta, #grupoPrestatario').hide();

        $('#selectTipo').on('change', function () {
            const tipo = $(this).val();
            $('#selectCuenta').val('');
            $('#selectPrestatario').empty();
            $('#grupoPrestatario').hide();
            cargarCuentas(tipo);
        });

        $('#selectCuenta').on('change', function () {
            const cuentaNombre = $('#selectCuenta option:selected').data('nombre') || '';
            cargarPrestatariosDesdeCuenta(cuentaNombre);
        });

        $('form').on('input change', validarFormulario);

        $('#modalMovimiento').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
            $('#selectCuenta').empty().append('<option value="">Seleccione cuenta</option>');
            $('#selectPrestatario').empty();
            $('#grupoCuenta, #grupoPrestatario').hide();
            $('#btnGuardar').prop('disabled', true);
        });
    });
</script>
@endpush

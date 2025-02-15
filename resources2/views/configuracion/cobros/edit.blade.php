<div class="modal fade" id="ModalEdit{{ $cobro->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success pt-3 py-4 pb-3">
                <h6 class="modal-title text-white">EDITAR COBRO</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-12">
                        <label class="mb-2">Nombre</label>
                        <input class="form-control form-control-solid" id="titulo" name="titulo" type="text" required value="{{ $cobro->titulo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Tipo de Cambio</label>
                        <input class="form-control form-control-solid" id="tipo" name="tipo" type="text" required value="{{ $cobro->tipo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Comision</label>
                        <input class="form-control form-control-solid" id="comision" name="comision" type="text" required value="{{ $cobro->comision }}" />
                    </div>

                    <div class="form-check mb-2 mt-2 col-md-6">
                        <input class="form-check-input" type="checkbox" value="@if($cobro->deposito) {{ $cobro->deposito }} @else No @endif" id="deposito" name="deposito" @if($cobro->deposito) checked  @endif />
                        <label class="form-check-label" for="deposito">Deposito en cuenta</label>
                    </div>

                    <input class="form-control form-control-solid" id="estatus" name="estatus" type="hidden" value="{{ $cobro->estatus }}" />
                </div>
            </div>

            <div class="modal-footer bg-light">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="button" class="btn btn-dark col-md-12 font-14" data-bs-dismiss="modal">CANCELAR</button>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="submit" class="btn btn-success col-md-12 font-14">ACTUALIZAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
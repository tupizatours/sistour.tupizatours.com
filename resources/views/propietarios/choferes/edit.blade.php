<div class="modal fade" id="ModalEdit{{ $chofer->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success pt-3 py-4 pb-3">
                <h6 class="modal-title text-white">EDITAR CHOFER</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Nombre</label>
                        <input class="form-control form-control-solid" id="nombre" name="nombre" type="text" required value="{{ $chofer->nombre }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Apellido</label>
                        <input class="form-control form-control-solid" id="apellido" name="apellido" type="text" required value="{{ $chofer->apellido }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Cédula de identidad</label>
                        <input class="form-control form-control-solid" id="cedula" name="cedula" type="number" required value="{{ $chofer->cedula }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Licencia de conducir</label>
                        <input class="form-control form-control-solid" id="licencia" name="licencia" type="text" required value="{{ $chofer->licencia }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Correo electrónico</label>
                        <input class="form-control form-control-solid" id="correo" name="correo" type="email" required value="{{ $chofer->correo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Celular</label>
                        <input class="form-control form-control-solid" id="celular" name="celular" type="number" required value="{{ $chofer->celular }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Número de cuenta</label>
                        <input class="form-control form-control-solid" id="cuenta" name="cuenta" type="number" required value="{{ $chofer->cuenta }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Banco</label>
                        <select class="form-control form-control-solid" id="bancos_id" name="bancos_id" type="select">
                            @if($chofer->bancos_id)
                                <option value="{{ $chofer->banco->id }}">{{ $chofer->banco->titulo }}</option>
                            @endif
                            <option value="">Seleccionar</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}">{{ $banco->titulo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Referencia</label>
                        <input class="form-control form-control-solid" id="referencia" name="referencia" type="text" value="{{ $chofer->referencia }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Celular de referencia</label>
                        <input class="form-control form-control-solid" id="celref" name="celref" type="number" value="{{ $chofer->celref }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Observaciones</label>
                        <input class="form-control form-control-solid" id="observaciones" name="observaciones" type="text" value="{{ $chofer->observaciones }}" />
                    </div>

                    <input id="estatus" name="estatus" type="hidden" value="{{ $chofer->estatus }}" />
                    <input id="foto" name="foto" type="hidden" value="1" />
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
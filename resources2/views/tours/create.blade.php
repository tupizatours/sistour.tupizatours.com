<div class="modal fade" id="ModalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary pt-3 py-4 pb-3">
                <h6 class="modal-title text-white">Crear nuevo tour</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <div class="row g-3 pt-3 pb-0 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Código</label>
                        <input class="form-control form-control-solid" id="codigo" name="codigo" type="text" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Titulo</label>
                        <input class="form-control form-control-solid" id="titulo" name="titulo" type="text" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Descripción</label>
                        <textarea class="form-control form-control-solid" id="descripcion" name="descripcion" rows="5" required></textarea>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Categoría</label>
                        <select class="form-control form-control-solid" id="categoria_id" name="categoria_id" type="select" required>
                            <option value="">Seleccionar</option>
                            @foreach($categorias as $categoria)
                                @if($categoria->estatus == 1)
                                    <option value="{{ $categoria->id }}">{{ $categoria->titulo }}</option>
                                @endif
                            @endforeach
                        </select>

                        <label class="mt-2 mb-2">Hora límite de reserva</label>
                        <input class="form-control form-control-solid" id="hor_lim" name="hor_lim" type="text" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Duración/días</label>
                        <input class="form-control form-control-solid" id="duracion" name="duracion" type="text" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Duración/noches</label>
                        <input class="form-control form-control-solid" id="noches" name="noches" type="text" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Tipo de tour</label>
                        <select class="form-control form-control-solid" id="tipo" name="tipo" type="select" required>
                            <option value="">Seleccionar</option>
                            <option value="Ambos">Ambos</option>
                            <option value="Compartido">Compartido</option>
                            <option value="Privado">Privado</option>
                        </select>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Mínimo de personas</label>
                        <input class="form-control form-control-solid" id="min_per" name="min_per" type="number" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Máximo de personas</label>
                        <input class="form-control form-control-solid" id="max_per" name="max_per" type="number" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Servicios por tour</label>
                        <select class="form-select" id="serv_tour" name="serv_tour[]" type="select" required data-placeholder="Seleccionar" multiple>
                            @foreach($servicios as $servicio)
                                @if($servicio->estatus == "1")
                                    <option value="{{ $servicio->id }}">{{ $servicio->titulo }}</option>
                                @endif
                            @endforeach
                            <option value="100">Guias</option>
                            <option value="101">Traductores</option>
                            <option value="102">Cocineros</option>
                            <option value="103">Choferes</option>
                            <option value="104">Vagonetas</option>
                            <option value="105">Caballos</option>
                            <option value="106">Bicicletas</option>
                        </select>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio unitario</label>
                        <input class="form-control form-control-solid" id="pre_uni" name="pre_uni" type="number" required />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio total</label>
                        <input class="form-control form-control-solid" id="pre_tot" name="pre_tot" type="number" required />
                    </div>
                </div>

                <div class="row g-3 pt-3 pb-0 col-md-12" id="hoteles_cont"></div>

                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Tickets</label>
                        <select class="form-select" id="tickets" name="tickets[]" type="select" required data-placeholder="Seleccionar" multiple>
                            @foreach($tickets as $ticket)
                                @if($ticket->estatus == "1")
                                    <option value="{{ $ticket->id }}">{{ $ticket->titulo }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Alquiler de accesorios</label>
                        <select class="form-select" id="accesorios" name="accesorios[]" type="select" required data-placeholder="Seleccionar" multiple>
                            @foreach($accesorios as $accesorio)
                                @if($accesorio->estatus == "1")
                                    <option value="{{ $accesorio->id }}">{{ $accesorio->titulo }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Alquiler de servicios</label>
                        <select class="form-select" id="turistas" name="turistas[]" type="select" required data-placeholder="Seleccionar" multiple>
                            @foreach($turistas as $turista)
                                @if($turista->estatus == "1")
                                    <option value="{{ $turista->id }}">{{ $turista->titulo }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" id="serv_cli" name="serv_cli" value="0">

                    <input class="form-control form-control-solid" id="estatus" name="estatus" type="hidden" value="1" />
                </div>
            </div>

            <div class="modal-footer bg-light">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="button" class="btn btn-dark col-md-12 font-14" data-bs-dismiss="modal">CANCELAR</button>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="submit" class="btn btn-primary col-md-12 font-14">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
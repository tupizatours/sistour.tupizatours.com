<div class="card-body pt-5 pb-5 p-4 fase" id="cuarta_fase" style="display: none;">
    <ul class="nav nav-tabs nav-primary" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#credito" role="tab" aria-selected="true">
                <div class="d-flex align-items-center">
                    <div class="tab-title">Tarjeta de crédito</div>
                </div>
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#transferencia" role="tab" aria-selected="false" tabindex="-1">
                <div class="d-flex align-items-center">
                    <div class="tab-title">Transferencia bancaria</div>
                </div>
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#qr" role="tab" aria-selected="false" tabindex="-1">
                <div class="d-flex align-items-center">
                    <div class="tab-title">QR bancario</div>
                </div>
            </a>
        </li>
    </ul>

    <div class="tab-content py-3">
        {{-- Tarjeta de crédito --}}
        <div class="tab-pane fade show active" id="credito" role="tabpanel">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @foreach($links as $link)
                                @if($link->estatus == "1")
                                    <tr>
                                        <td>{{ $link->nombre }}</td>
                                        <td>{{ $link->descripcion }}</td>
                                        <td>
                                            <a href="{{ $link->url }}" target="_blank" class="btn btn-primary btn-sm radius-30 px-4 col-md-12">
                                                Pagar ahora
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Transferencia bancaria --}}
        <div class="tab-pane fade" id="transferencia" role="tabpanel">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @foreach($onlines as $online)
                                @if($online->estatus == "1")
                                    <tr>
                                        <td>{{ $online->nombre }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- QR bancario --}}
        <div class="tab-pane fade" id="qr" role="tabpanel">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @foreach($qrs as $qr)
                                @if($qr->estatus == "1")
                                    <tr>
                                        <td>
                                            <img src="{{ asset('panelqrs') }}/{{ $qr->file }}" alt="QR" width="200" height="200">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Botones finales --}}
    <div class="row g-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-center gap-2">
                <a href="javascript:;" class="btn btn-danger regresar col-md-4" data-prev="tercera_fase">
                    <i class="fadeIn animated bx bx-arrow-to-left"></i>Regresar
                </a>
                <button type="submit" class="btn btn-primary continuar col-md-4">
                    Reservar <i class="fadeIn animated bx bx-arrow-to-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

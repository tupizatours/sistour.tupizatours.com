@props([
    'tour',
    'tickets',
    'hoteles',
    'habitaciones',
    'accesorios',
    'turistas',
    'countries',
    'alergias',
    'alimentos',
    'links',
    'onlines',
    'qrs'
])

<form action="{{ route('reservas.store') }}" class="uploader" method="POST" id="file-upload-form" enctype="multipart/form-data">
    @csrf

    @php
        $originalDate = $tour->created_at;
        $newDate = date("m/d/Y", strtotime($originalDate));
    @endphp

    <input type="hidden" id="hor_lim" name="hor_lim" value="{{ $tour->hor_lim }}" />
    <input type="hidden" id="max_per" name="max_per" value="{{ $tour->max_per }}" />
    <input type="hidden" id="pre_tot" name="pre_tot" value="{{ $tour->pre_tot }}" />
    <input type="hidden" id="pre_uni" name="pre_uni" value="{{ $tour->pre_uni }}" />
    <input type="hidden" id="created_at" name="created_at" value="{{ $newDate }}" />
    <input type="hidden" id="tour_id" name="tour_id" value="{{ $tour->id }}" />
    <input type="hidden" id="estatus" name="estatus" value="1" />

    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-5">
            <div class="card">
                <div class="card border-primary mb-0">
                    <!-- Fases del formulario -->
                    @include('components.reserva.fases.primer-fase', ['tour' => $tour])
                    @include('components.reserva.fases.segunda-fase', compact('countries', 'alergias', 'alimentos'))
                    @include('components.reserva.fases.tercera-fase', compact('tour', 'tickets', 'hoteles', 'habitaciones', 'accesorios', 'turistas'))
                    @include('components.reserva.fases.cuarta-fase', compact('links', 'onlines', 'qrs'))
                </div>
            </div>
        </div>

        <div class="col-md-5">
            @include('components.reserva.resumen-final', ['tour' => $tour])
        </div>
    </div>
</form>


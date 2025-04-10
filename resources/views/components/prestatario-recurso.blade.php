@props([
    'prestatarioId',
    'prestatarioName',
    'prestatarioLabel',
    'prestatarioItems',
    'prestatarioSelected' => null,
    'prestatarioOnchange' => null,

    'recursoId',
    'recursoName',
    'recursoLabel',
    'recursoItems',
    'recursoSelected' => null,
    'recursoOnchange' => null,

    'tarifaId',
    'tarifaValue' => null,
])

<div class="row g-3 pt-3 pb-2 col-md-12 prelative">
    {{-- Selector de Prestatario --}}
    <div class="form-group mb-2 mt-2 col-md-4">
        <label class="mb-2">{{ $prestatarioLabel }}</label>
        <select class="form-control form-control-solid"
                id="{{ $prestatarioId }}"
                name="{{ $prestatarioName }}"
                onchange="{{ $prestatarioOnchange }}"
                required>
            <option value="">Seleccionar</option>
            @foreach($prestatarioItems as $item)
                <option value="{{ $item->id }}" @selected($item->id == $prestatarioSelected)>
                    {{ $item->nombre }} {{ $item->apellido }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Selector de Recurso (caballo, vagoneta, etc.) --}}
    <div class="form-group mb-2 mt-2 col-md-4">
        <label class="mb-2">{{ $recursoLabel }}</label>
        <select class="form-control form-control-solid"
                id="{{ $recursoId }}"
                name="{{ $recursoName }}"
                onchange="{{ $recursoOnchange }}">
            <option value="">Seleccionar</option>
            @foreach($recursoItems as $recurso)
                <option value="{{ $recurso->id }}"
                        data-tarifa="{{ number_format($recurso->costo, 2, '.', '') }}"
                        @selected($recurso->id == $recursoSelected)>
                    {{ $recurso->nombre ?? $recurso->marca }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Campo de Tarifa --}}
    <div class="form-group mb-2 mt-2 col-md-4">
        <label class="mb-2">Precio costo</label>
        <input class="form-control form-control-solid"
               id="{{ $tarifaId }}"
               name="{{ $tarifaId }}"
               type="number"
               value="{{ $tarifaValue }}" />
    </div>
</div>

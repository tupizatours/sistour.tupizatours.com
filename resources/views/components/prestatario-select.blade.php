@props([
    'id',
    'name',
    'label',
    'items',
    'selected' => null,
    'onchange' => '',
    'placeholder' => 'Seleccionar',
    'tarifa' => null,
    'valueTarifa' => null,
    'tarifaField' => null,
    'disabled' => false,
    'required' => false,
])

<div class="form-group mb-2 mt-2 col-md-{{ $col ?? 6 }}">
    <label class="mb-2">{{ $label }}</label>
    <select
        class="form-control form-control-solid"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        onchange="{{ $onchange }}"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($items as $item)
            <option
                value="{{ $item->id }}"
                data-tarifa="{{ number_format($item->{$tarifaField}, 2, '.', '') }}"
                {{ $selected == $item->id ? 'selected' : '' }}
            >
                {{ $item->nombre . ' ' . ($item->apellido ?? '') }}
            </option>
        @endforeach
    </select>
</div>

@if($tarifa)
    <div class="form-group mb-2 mt-2 col-md-{{ $col ?? 6 }}">
        <label class="mb-2">Precio costo</label>
        <input
            class="form-control form-control-solid"
            id="{{ $tarifa }}"
            name="{{ $tarifa }}"
            type="number"
            value="{{ $valueTarifa }}"
        />
    </div>
@endif

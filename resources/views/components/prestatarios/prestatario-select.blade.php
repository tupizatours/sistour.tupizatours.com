@props([
    'id',
    'name',
    'label',
    'items',
    'selected' => null,
    'onchange' => '',
    'col' => 6,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Seleccionar',
    'tarifa' => null,
    'valueTarifa' => null,
    'tarifaField' => 'tarifa',
    'labelField' => 'nombre', // nuevo prop: por defecto muestra el nombre
])

<div class="form-group mb-2 mt-2 col-md-{{ $col }}">
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
                {{ $item->{$labelField} ?? ($item->nombre . ' ' . ($item->apellido ?? '')) }}
            </option>
        @endforeach
    </select>

    @if ($tarifa)
        <input
            class="form-control form-control-solid mt-2"
            id="{{ $tarifa }}"
            name="{{ $tarifa }}"
            type="number"
            value="{{ $valueTarifa }}"
        />
    @endif
</div>

@extends('layouts.tienda')

@section('template_title')
    Vuelto generado
@endsection


@section('content')
<div class="row">
    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <h3 class="text-success mb-4">¡Pago recibido!</h3>

                <p><strong>Reserva:</strong> {{ $codigo_reserva }}</p>
                <p><strong>Monto ingresado:</strong> Bs. {{ number_format($monto_convertido, 2, '.', '') }}</p>
                <p><strong>Monto aplicado:</strong> Bs. {{ number_format($monto_aplicado, 2, '.', '') }}</p>

                <div class="alert alert-warning mt-3">
                    <strong>Vuelto a entregar:</strong>
                    <span class="fs-4 text-danger">Bs. {{ number_format($vuelto, 2, '.', '') }}</span>
                </div>
                <a href="{{ url('ventas/resclis/' . $rescli_id) }}" class="btn btn-primary mt-4">
                    Volver a la reserva
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
</div>
@endsection
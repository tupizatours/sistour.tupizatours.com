@extends('layouts.tienda')

@section('template_title')
    Vuelto generado
@endsection


@section('content')
<div class="col-md-8">
    <div class="card shadow mt-4">
        <div class="card-body text-center py-5">
            <h3 class="text-success mb-4">¡Pago registrado!</h3>
            <p class="mb-3">Se registró el pago de <strong>Bs. {{ number_format($monto_aplicado, 2) }}</strong></p>
            <p>El cliente entregó: <strong>Bs. {{ number_format($monto_ingresado, 2) }}</strong></p>
            <p class="text-danger"><strong>Vuelto a entregar: Bs. {{ number_format($vuelto, 2) }}</strong></p>
            <a href="{{ url('ventas/resclis/' . $rescli_id) }}" class="btn btn-primary mt-4">Volver a la reserva</a>
        </div>
    </div>
</div>
@endsection
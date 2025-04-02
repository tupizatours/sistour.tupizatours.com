@extends('layouts.tienda')

@section('template_title')
    Vuelto de la reserva
@endsection

@section('content')
<div class="row">
    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <h3 class="text-success mb-4">¡Gracias, {{ $nombre }}!</h3>
                <p>Tu pago ha sido registrado exitosamente.</p>
                <p>Se ha aplicado un monto de <strong>Bs. {{ number_format($monto_aplicado, 2, '.', '') }}</strong> a tu reserva.</p>
                <p>Tu vuelto es: <strong class="text-danger">Bs. {{ number_format($vuelto, 2, '.', '') }}</strong></p>

                <a href="{{ url('ventas/resclis/' . $rescli_id) }}" class="btn btn-primary mt-4">Volver a la reserva</a>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
</div>
@endsection
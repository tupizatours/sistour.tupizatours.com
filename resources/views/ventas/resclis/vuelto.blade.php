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
                        <h3 class="text-primary mb-4">¡Pago procesado con éxito!</h3>
                        <p><strong>Código de reserva:</strong> {{ $codigo_reserva }}</p>
                        <p><strong>Monto entregado:</strong> {{ $monto_original }}</p>
                        <p><strong>Equivalente en Bs:</strong> {{ number_format($monto_convertido, 2, '.', '') }}</p>
                        <p><strong>Monto aplicado:</strong> {{ number_format($monto_aplicado, 2, '.', '') }}</p>
                        <p><strong>Vuelto a entregar:</strong> <span class="text-success">Bs. {{ number_format($vuelto, 2, '.', '') }}</span></p>
                        
                        <a href="{{ url('ventas/resclis/' . $rescli_id) }}" class="btn btn-primary mt-4">Ir a la reserva</a>
                    </div>
                </div>
            </div>

            <div class="col-md-2"></div>
        </div>
@endsection

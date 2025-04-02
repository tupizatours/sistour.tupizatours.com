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
                    <h3 class="text-success mb-4">¡Pago realizado exitosamente!</h3>

                    <p><strong>Código de reserva:</strong> {{ $codigo_reserva }}</p>
                    <p><strong>Monto ingresado:</strong> Bs. {{ number_format($monto_ingresado, 2, '.', '') }}</p>
                    <p><strong>Monto aplicado:</strong> Bs. {{ number_format($monto_pagado, 2, '.', '') }}</p>
                    <p><strong>Vuelto a entregar:</strong> 
                        <span class="text-danger fs-4">Bs. {{ number_format($vuelto, 2, '.', '') }}</span>
                    </p>

                    <a href="{{ url('ventas/resclis/' . $rescli_id) }}" class="btn btn-primary mt-4">
                        Volver a la reserva
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-2"></div>
    </div>
@endsection

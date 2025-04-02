@extends('layouts.tienda')

@section('template_title')
    Vuelto entregado
@endsection

@section('estilos')
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #444;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="card-body text-center">
                <h3 class="text-success mb-4">¡Pago procesado correctamente!</h3>
                <p>La reserva <strong>{{ $codigo_reserva }}</strong> ha sido registrada.</p>

                <div class="info-group">
                    <p><span class="info-label">Monto ingresado:</span> {{ number_format($monto_ingresado, 2) }} {{ $metodo === 'efectivo_bs' ? 'Bs' : 'USD' }}</p>
                    <p><span class="info-label">Monto aplicado al pago:</span> {{ number_format($monto_pagado, 2) }} {{ $metodo === 'efectivo_bs' ? 'Bs' : 'USD' }}</p>
                    <p><span class="info-label">Vuelto:</span>
                        {{ number_format($vuelto_bs, 2) }} Bs
                        @if($vuelto_moneda)
                            ({{ number_format($vuelto_moneda, 2) }} {{ strtoupper($metodo) }})
                        @endif
                    </p>
                </div>

                <a href="{{ url('ventas/resclis/' . $rescli_id) }}" class="btn btn-custom mt-3">Volver a la reserva</a>
            </div>
        </div>
    </div>
</div>
@endsection
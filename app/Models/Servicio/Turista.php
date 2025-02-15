<?php

namespace App\Models\Servicio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turista extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'costo',
        'venta',
        'estatus'
    ];

    protected $table = 'turistas';
}

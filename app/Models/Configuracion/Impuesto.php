<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'porcentaje',
        'estatus'
    ];

    protected $table = 'impuestos';
}

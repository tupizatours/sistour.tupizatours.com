<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'simbolo',
        'estatus'
    ];

    protected $table = 'monedas';
}

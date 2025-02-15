<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'tipo',
        'comision',
        'deposito',
        'estatus'
    ];

    protected $table = 'cobros';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'costo',
        'venta',
        'estatus'
    ];

    protected $table = 'servicios';
}

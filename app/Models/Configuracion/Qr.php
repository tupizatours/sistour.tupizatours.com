<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'file',
        'estado',
        'estatus'
    ];

    protected $table = 'qrs';
}

<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Online extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estatus'
    ];

    protected $table = 'onlines';
}

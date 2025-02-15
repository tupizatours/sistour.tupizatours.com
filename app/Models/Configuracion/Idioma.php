<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'titulo',
        'estatus'
    ];

    protected $table = 'idiomas';
}

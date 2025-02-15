<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'fondo',
        'titulo',
        'estatus'
    ];

    protected $table = 'configuracions';
}

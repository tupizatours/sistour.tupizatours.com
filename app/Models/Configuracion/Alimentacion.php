<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimentacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'estatus'
    ];

    protected $table = 'alimentacions';
}

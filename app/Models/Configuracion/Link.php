<?php

namespace App\Models\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'url',
        'estatus'
    ];

    protected $table = 'links';
}

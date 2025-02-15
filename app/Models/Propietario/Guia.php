<?php

namespace App\Models\Propietario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo',
        'cedula',
        'acreditacion',
        'correo',
        'celular',
        'idiomas_id',
        'tarifa',
        'cuenta',
        'bancos_id',
        'referencia',
        'celref',
        'observaciones',
        'file',
        'estatus'
    ];

    protected $table = 'guias';

    public function banco() {
        return $this->belongsTo('App\Models\Configuracion\Banco', 'bancos_id', 'id');
    }
}

<?php

namespace App\Models\Propietario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traductor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
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

    protected $table = 'traductores';

    public function banco() {
        return $this->belongsTo('App\Models\Configuracion\Banco', 'bancos_id', 'id');
    }
}

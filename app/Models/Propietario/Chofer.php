<?php

namespace App\Models\Propietario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'licencia',
        'numero',
        'correo',
        'celular',
        'cuenta',
        'bancos_id',
        'referencia',
        'celref',
        'observaciones',
        'file',
        'estatus'
    ];

    protected $table = 'choferes';

    public function banco() {
        return $this->belongsTo('App\Models\Configuracion\Banco', 'bancos_id', 'id');
    }
}

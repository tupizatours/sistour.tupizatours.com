<?php

namespace App\Models\Propietario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cocinero extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'correo',
        'celular',
        'tarifa',
        'cuenta',
        'bancos_id',
        'referencia',
        'celref',
        'observaciones',
        'file',
        'estatus'
    ];

    protected $table = 'cocineros';

    public function banco() {
        return $this->belongsTo('App\Models\Configuracion\Banco', 'bancos_id', 'id');
    }
}

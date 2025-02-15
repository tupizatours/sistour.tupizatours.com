<?php

namespace App\Models\Servicio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    use HasFactory;

    protected $fillable = [
        'propietario_id',
        'nombre',
        'costo',
        'venta',
        'estatus'
    ];

    protected $table = 'bicicletas';

    public function propietario() {
        return $this->belongsTo('App\Models\Propietario', 'propietario_id', 'id');
    }
}

<?php

namespace App\Models\Servicio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vagoneta extends Model
{
    use HasFactory;

    protected $fillable = [
        'propietario_id',
        'marca',
        'placa',
        'color',
        'modelo',
        'costo',
        'venta',
        'estatus'
    ];

    protected $table = 'vagonetas';

    public function propietario() {
        return $this->belongsTo('App\Models\Propietario', 'propietario_id', 'id');
    }
}

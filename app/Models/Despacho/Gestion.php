<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'reserva_id',
        'tour_id',
        'servicio_id',
        'servicio_t',
        'guia_id',
        'guia_t',
        'traductor_id',
        'traductor_t',
        'cocinero_id',
        'cocinero_t',
        'chofer_id',
        'chofer_t',
        'vagoneta_id',
        'provag_id',
        'vagoneta_t',
        'caballo_id',
        'procab_id',
        'caballo_t',
        'bicicleta_id',
        'probic_id',
        'bicicleta_t',
        'estado',
        'estatus'
    ];

    public function reserva() {
        return $this->belongsTo('App\Models\Reserva', 'reserva_id', 'id');
    }

    public function tour() {
        return $this->belongsTo('App\Models\Tour', 'tour_id', 'id');
    }

    public function provag() {
        return $this->belongsTo('App\Models\Propietario', 'provag_id', 'id');
    }

    public function procab() {
        return $this->belongsTo('App\Models\Propietario', 'procab_id', 'id');
    }

    public function probic() {
        return $this->belongsTo('App\Models\Propietario', 'probic_id', 'id');
    }

    public function servicio() {
        return $this->belongsTo('App\Models\Servicio', 'servicio_id', 'id');
    }

    public function guia() {
        return $this->belongsTo('App\Models\Propietario\Guia', 'guia_id', 'id');
    }

    public function traductor() {
        return $this->belongsTo('App\Models\Propietario\Traductor', 'traductor_id', 'id');
    }

    public function cocinero() {
        return $this->belongsTo('App\Models\Propietario\Cocinero', 'cocinero_id', 'id');
    }

    public function chofer() {
        return $this->belongsTo('App\Models\Propietario\Traductor', 'chofer_id', 'id');
    }

    public function bicicleta() {
        return $this->belongsTo('App\Models\Servicio\Bicicleta', 'bicicleta_id', 'id');
    }

    public function caballo() {
        return $this->belongsTo('App\Models\Servicio\Caballo', 'caballo_id', 'id');
    }

    public function vagoneta() {
        return $this->belongsTo('App\Models\Servicio\Vagoneta', 'vagoneta_id', 'id');
    }

    protected $table = 'gestions';
}

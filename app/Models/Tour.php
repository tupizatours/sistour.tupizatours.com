<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'titulo',
        'descripcion',
        'categoria_id',
        'duracion',
        'noches',
        'min_per',
        'max_per',
        'hor_lim',
        'tipo',
        'serv_tour',
        'serv_cli',
        'pre_uni',
        'pre_tot',
        'tickets',
        'hoteles',
        'accesorios',
        'turistas',
        'estatus'
    ];

    protected $table = 'tours';

    public function categoria() {
        return $this->belongsTo('App\Models\Tour\Categoria', 'categoria_id', 'id');
    }

    public function hoteles() {
        return $this->belongsToMany('App\Models\Servicio\Hotel', 'hotel_tour')
                    ->withPivot('dia')  // Incluye el campo 'dia' de la tabla pivote
                    ->withTimestamps(); // Opcional si tienes timestamps en la tabla pivote
    }

    public function reservas() {
        return $this->hasMany('App\Models\Reserva', 'tour_id', 'id');
    }
}

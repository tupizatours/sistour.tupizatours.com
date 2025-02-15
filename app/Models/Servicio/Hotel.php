<?php

namespace App\Models\Servicio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'tipo',
        'estado',
        'estatus'
    ];

    protected $table = 'hoteles';

    public function habitaciones() {
        return $this->hasMany('App\Models\Servicio\Habitacion', 'hotel_id', 'id');
    }

    public function tours() {
        return $this->belongsToMany('App\Models\Tour', 'hotel_tour')
                    ->withPivot('dia')  // Incluye el campo 'dia' de la tabla pivote
                    ->withTimestamps(); // Opcional si tienes timestamps en la tabla pivote
    }
}

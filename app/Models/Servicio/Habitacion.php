<?php

namespace App\Models\Servicio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'titulo',
        'costo',
        'cos_ext',
        'nacionales',
        'extranjeros',
        'estatus'
    ];

    protected $table = 'habitaciones';

    public function hotel() {
        return $this->belongsTo('App\Models\Servicio\Hotel', 'hotel_id', 'id');
    }
}

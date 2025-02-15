<?php

namespace App\Models\Servicio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'costo',
        'cos_ext',
        'nacionales',
        'extranjeros',
        'estatus'
    ];

    protected $table = 'tickets';
}

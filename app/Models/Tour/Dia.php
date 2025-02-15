<?php

namespace App\Models\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'hoteles',
        'dias',
        'estatus'
    ];

    protected $table = 'dias';
}

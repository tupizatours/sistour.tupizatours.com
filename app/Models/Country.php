<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'iso',
        'estatus',
        'phone_code',
    ];

    protected $table = 'countries';
}

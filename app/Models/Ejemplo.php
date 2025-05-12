<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ejemplo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_materia'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id_Registro';
    
    protected $fillable = [
        'Anio',
        'Id_Reg_A',
        'Cv_Alumno',
        'Nombre_Alumno',
        'Gen',
        'Carrera',
        'email',
        'Mat_1',
        'Mat_2',
        'Mat_3',
        'Escuela',
        'TBaja',
        'Inc_Carr',
        'Empresa',
        'Titulacion'
    ];
}
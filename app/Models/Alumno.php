<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención de Laravel)
    protected $table = 'alumnos';

    // Primary Key (opcional si es 'id')
    protected $primaryKey = 'Id_Registro';

    // Campos asignables masivamente (Mass Assignment)
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

    // Casts de tipos (opcional)
    protected $casts = [
        'Anio' => 'integer',
        'Id_Reg_A' => 'integer',
        'Cv_Alumno' => 'integer',
        'Gen' => 'integer',
        // Los timestamps ya vienen por defecto
    ];
}

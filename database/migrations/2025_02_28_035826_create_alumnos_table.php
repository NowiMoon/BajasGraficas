<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigIncrements('Id_Registro');
            $table->unsignedBigInteger('Anio');
            $table->unsignedBigInteger('Id_Reg_A');
            $table->unsignedBigInteger('Cv_Alumno');
            $table->string('Nombre_Alumno', 100);
            $table->unsignedBigInteger('Gen');
            $table->string('Carrera', 100);
            $table->string('email', 50);
            $table->string('Mat_1', 50)->nullable();
            $table->string('Mat_2', 50)->nullable();
            $table->string('Mat_3', 50)->nullable();
            $table->string('Escuela',200)->nullable();
            $table->string('TBaja',100);
            $table->string('Inc_Carr', 1000)->nullable();
            $table->string('Empresa',200)->nullable();
            $table->string('Titulacion',100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};

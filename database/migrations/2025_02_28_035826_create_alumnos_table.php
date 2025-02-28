<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigIncrements('Id_Registro');
            $table->unsignedBigInteger('Anio');
            $table->unsignedBigInteger('Id_Reg_A');
            $table->unsignedBigInteger('Cv_Alumno');
            $table->string('Nombre_Alumno', 50);
            $table->unsignedBigInteger('Gen');
            $table->unsignedBigInteger('Cv_Carrera');
            $table->string('email', 30);
            $table->unsignedBigInteger('Cv_Mat_1');
            $table->unsignedBigInteger('Cv_Mat_2')->nullable();
            $table->unsignedBigInteger('Cv_Mat_3')->nullable();
            $table->unsignedBigInteger('Id_Escuela')->nullable();
            $table->unsignedBigInteger('Id_TBaja');
            $table->string('Inc_Carr', 100)->nullable();
            $table->unsignedBigInteger('Id_Empresa')->nullable();
            $table->unsignedBigInteger('Id_Titulacion')->nullable();

            $table->timestamps();

            //$table->foreign('CV_Carrera')->references('CV_Carrera')->on('Carrera')->onDelete('cascade');
            //$table->foreign('Cv_Mat_1')->references('')->on('Personal')->onDelete('cascade'); //TODO: Is subject table missing??
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};

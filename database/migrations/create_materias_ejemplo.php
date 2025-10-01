<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materia', function (Blueprint $table) {
            $table->string('nombre_materia', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materia');
    }
};

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
        Schema::create('asignacion_citas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('cita_fecha');
            $table->foreignId('id_vehiculo')
                ->references('id')
                ->on('vehiculos')
                ->onDelete('cascade');
            $table->foreignId('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->bigInteger('tiempo_aproximado')->default(0);
            $table->string('estado')->default('creado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_citas');
    }
};

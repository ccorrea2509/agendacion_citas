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
        Schema::create('procedimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cita')
                ->references('id')
                ->on('asignacion_citas')
                ->onDelete('cascade');
            $table->foreignId('id_puesto_trabajo')
                ->references('id')
                ->on('puesto_trabajos')
                ->onDelete('cascade');
            $table->foreignId('id_personal')
                ->references('id')
                ->on('personals')
                ->onDelete('cascade');
            $table->foreignId('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignId('id_servicio')
                ->references('id')
                ->on('servicios')
                ->onDelete('cascade');
            $table->integer('precio');
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedimientos');
    }
};

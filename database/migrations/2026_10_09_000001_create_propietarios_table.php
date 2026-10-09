<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('tipo_documento');
            $table->string('documento')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->string('direccion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
};

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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->enum('tipo_habitacion', ['sencilla', 'bañera', 'jacuzzi', 'doble', 'triple', 'multiple']);
            $table->integer('capacidad');
            $table->decimal('precio', 10, 2);
            $table->string('descripcion');
            $table->enum('estado', ['disponible', 'mantenimiento'])->default('disponible');
            $table->decimal('precio_persona_adicional', 10, 2)->default(40000.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};

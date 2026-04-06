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
        Schema::create('padron_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('direccion', 500);
            $table->enum('estado', ['activo', 'cortado'])->default('activo');
            $table->foreignId('tarifa_id')->constrained('tarifas')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();

            $table->index('codigo');
            $table->index('estado');
            $table->index('tarifa_id');
            $table->index(['nombre', 'apellido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padron_usuarios');
    }
};

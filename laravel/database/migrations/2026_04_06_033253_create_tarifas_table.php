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
    Schema::create('tarifas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->decimal('monto', 8, 2);
        $table->string('descripcion')->nullable();
        $table->date('vigente_desde');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};

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
    Schema::create('pagos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('padron_usuario_id')->constrained();
        $table->foreignId('user_id')->constrained();
        $table->string('periodo');
        $table->decimal('monto_cuota', 8, 2);
        $table->decimal('monto_deuda', 8, 2)->default(0);
        $table->decimal('monto_multas', 8, 2)->default(0);
        $table->decimal('monto_total', 8, 2);
        $table->string('numero_serie')->unique()->nullable();
        $table->enum('estado', ['pagado','pendiente'])->default('pendiente');
        $table->string('pdf_path')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

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
        Schema::create('multas_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('padron_usuario_id')->constrained('padron_usuarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('multa_id')->constrained('multas')->onUpdate('cascade')->onDelete('restrict');
            $table->string('mes', 7)->comment('Formato YYYY-MM');
            $table->decimal('monto', 10, 2);
            $table->boolean('pagada')->default(false);
            $table->timestamps();

            $table->index('padron_usuario_id');
            $table->index('multa_id');
            $table->index('mes');
            $table->index('pagada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multas_usuario');
    }
};

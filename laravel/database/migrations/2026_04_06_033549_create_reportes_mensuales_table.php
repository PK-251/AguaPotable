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
        Schema::create('reportes_mensuales', function (Blueprint $table) {
            $table->id();
            $table->string('periodo', 7)->unique()->comment('Formato YYYY-MM');
            $table->decimal('total_ingresos', 12, 2)->default(0);
            $table->decimal('total_egresos', 12, 2)->default(0);
            $table->decimal('total_pendientes', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->enum('estado', ['pendiente', 'aprobado'])->default('pendiente');
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();

            $table->index('periodo');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_mensuales');
    }
};

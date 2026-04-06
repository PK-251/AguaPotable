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
        Schema::create('egresos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 500);
            $table->string('categoria');
            $table->decimal('monto', 10, 2);
            $table->date('fecha');
            $table->string('proveedor')->nullable();
            $table->string('periodo', 7)->comment('Formato YYYY-MM');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();

            $table->index('categoria');
            $table->index('fecha');
            $table->index('periodo');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egresos');
    }
};

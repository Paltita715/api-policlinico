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
        Schema::create('farmacos', function (Blueprint $table) {
            $table->integer('id_farmaco', true, true)->from(100000)->primary(true);
            $table->string('nombre', 50);
            $table->string('compuesto', 200);
            $table->integer('unidades_por_paquete');
            $table->longText('descripcion');
            $table->string('reg_sanitario', 12);
            $table->integer('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmacos');
    }
};

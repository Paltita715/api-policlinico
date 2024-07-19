<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * tabla: users (USADO PARA REGISTRAR A LOS USUARIOS DEL ADMINISTRADOR DE CONTENIDOS, MODIFICAR CON CUIDADO)
     * atributos:
     *     id: int
     *     username: text, unique
     *     password: text, pasado antes por bcrypt
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password', 256);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

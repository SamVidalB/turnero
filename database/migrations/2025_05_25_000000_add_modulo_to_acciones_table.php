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
        Schema::table('acciones', function (Blueprint $table) {
            $table->string('modulo')->nullable()->after('ruta'); // Añade el campo modulo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acciones', function (Blueprint $table) {
            $table->dropColumn('modulo'); // Elimina el campo modulo si se hace rollback
        });
    }
};

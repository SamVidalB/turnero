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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->char('prefijo', 2);
            $table->smallInteger('consecutivo')->unsigned();
            $table->foreignId('paciente_id')->constrained();
            $table->foreignId('sede_id')->constrained();
            $table->boolean('estado')->default(1)->comment('1=toma turno | 2=admisión | 3=consulta | 4=postconsulta | 5=finalizado');
            
            $table->foreignId('usuario_admision_id')->constrained('usuarios')->nullable();
            $table->foreignId('usuario_consulta_id')->constrained('usuarios')->nullable();
            $table->foreignId('usuario_postconsulta_id')->constrained('usuarios')->nullable();

            $table->time('hora_admision')->nullable();
            $table->time('hora_consulta')->nullable();
            $table->time('hora_postconsulta')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};

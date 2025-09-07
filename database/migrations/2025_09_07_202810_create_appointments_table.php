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
       Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');             // Ej: "Cita con el cardiólogo"
            $table->date('date');                // Fecha de la cita
            $table->time('time');                // Hora de la cita
            $table->string('location')->nullable();  // Lugar (opcional)
            $table->text('notes')->nullable();       // Notas adicionales
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

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
        Schema::create('soutenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('users');
            $table->foreignId('directeur_id')->constrained('users');
            $table->string('titre');
            $table->string('filiere');
            $table->enum('type', ['licence', 'master', 'doctorat']);
            $table->date('date')->nullable();
            $table->time('heure')->nullable();
            $table->foreignId('salle_id')->nullable()->constrained('salles');
            $table->enum('statut', ['brouillon', 'planifiee', 'confirmee', 'realisee', 'annulee'])->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soutenances');
    }
};

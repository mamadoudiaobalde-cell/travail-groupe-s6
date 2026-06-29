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
        Schema::create('pvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soutenance_id')->unique()->constrained('soutenances');
            $table->decimal('note', 4, 2)->nullable();
            $table->string('mention')->nullable();
            $table->text('observations')->nullable();
            $table->enum('status', ['brouillon', 'en_validation', 'valide', 'signe', 'archive'])->default('brouillon');
            $table->string('fichier_pdf')->nullable();
            $table->date('signe_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pvs');
    }
};

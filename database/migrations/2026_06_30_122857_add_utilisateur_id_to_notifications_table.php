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
<<<<<<<< HEAD:database/migrations/2026_06_18_183510_create_salles_table.php
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->integer('capacite')->nullable();
            $table->string('localisation')->nullable();
            $table->text('equipements')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
========
        Schema::table('notifications', function (Blueprint $table) {
            //
>>>>>>>> layebara-tech:database/migrations/2026_06_30_122857_add_utilisateur_id_to_notifications_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
};

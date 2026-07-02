<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            $table->string('titre')->nullable()->after('utilisateur_id');
            $table->text('message')->nullable()->after('titre');
            $table->string('type')->default('info')->after('message');
            $table->string('lien')->nullable()->after('type');
            $table->boolean('lue')->default(false)->after('lien');
            $table->boolean('email_envoye')->default(false)->after('lue');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn([
                'titre',
                'message',
                'type',
                'lien',
                'lue',
                'email_envoye'
            ]);
        });
    }
};
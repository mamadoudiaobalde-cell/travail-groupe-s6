<?php
<<<<<<< HEAD

=======
>>>>>>> origin/ibrahimadev
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
<<<<<<< HEAD
<<<<<<<< HEAD:database/migrations/2026_06_18_183510_create_salles_table.php
=======
>>>>>>> origin/ibrahimadev
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->integer('capacite')->nullable();
            $table->string('localisation')->nullable();
            $table->text('equipements')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
<<<<<<< HEAD
========
        Schema::table('notifications', function (Blueprint $table) {
            //
>>>>>>>> layebara-tech:database/migrations/2026_06_30_122857_add_utilisateur_id_to_notifications_table.php
=======
>>>>>>> origin/ibrahimadev
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
};
=======
        Schema::dropIfExists('salles');
    }
};
>>>>>>> origin/ibrahimadev

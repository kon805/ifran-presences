<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // D'abord, modifier la colonne pour permettre temporairement NULL
        Schema::table('classes', function (Blueprint $table) {
            $table->string('statut_temp')->nullable();
        });

        // Copier les données
        DB::statement("UPDATE classes SET statut_temp = statut");

        // Supprimer l'ancienne colonne
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        // Créer la nouvelle colonne avec les bonnes valeurs
        Schema::table('classes', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'termine'])->default('en_cours');
        });

        // Copier les données
        DB::statement("UPDATE classes SET statut = CASE WHEN statut_temp = 'actif' THEN 'en_cours' ELSE statut_temp END");

        // Supprimer la colonne temporaire
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('statut_temp');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->enum('statut', ['actif', 'termine'])->default('actif');
        });
    }
};

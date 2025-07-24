<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Supprimer d'abord l'ancienne colonne statut
            $table->dropColumn('statut');
        });

        Schema::table('classes', function (Blueprint $table) {
            // Recréer la colonne avec les bonnes valeurs pour le statut
            // 'en_cours' = en cours, 'termine' = terminé
            $table->enum('statut', ['en_cours', 'termine'])->default('en_cours')->after('semestre');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Supprimer la nouvelle colonne
            $table->dropColumn('statut');

            // Restaurer l'ancienne colonne avec ses valeurs d'origine
            // 'actif' = actif (ancienne valeur), 'termine' = terminé
            $table->enum('statut', ['actif', 'termine'])->default('actif')->after('semestre');
        });
    }
};

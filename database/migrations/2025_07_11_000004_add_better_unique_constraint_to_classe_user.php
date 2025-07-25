<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // D'abord, vider la table pour éviter les conflits
        DB::table('classe_user')->truncate();

        // Supprimer l'ancienne contrainte
        Schema::table('classe_user', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });

        // Ajouter les colonnes d'année et de semestre à la table pivot
        Schema::table('classe_user', function (Blueprint $table) {
            $table->string('annee_academique')->nullable();
            $table->string('semestre')->nullable();
            // Ajouter un index unique sur user_id, annee_academique et semestre
            $table->unique(['user_id', 'annee_academique', 'semestre'], 'unique_etudiant_par_semestre');
        });

        // Créer un trigger pour remplir automatiquement les colonnes
        DB::unprepared('
            CREATE TRIGGER update_classe_user_details BEFORE INSERT ON classe_user
            FOR EACH ROW
            BEGIN
                SET NEW.annee_academique = (SELECT annee_academique FROM classes WHERE id = NEW.classe_id);
                SET NEW.semestre = (SELECT semestre FROM classes WHERE id = NEW.classe_id);
            END;
        ');
    }

    public function down()
    {
        // Supprimer le trigger
        DB::unprepared('DROP TRIGGER IF EXISTS update_classe_user_details');

        // Supprimer les colonnes et l'index
        Schema::table('classe_user', function (Blueprint $table) {
            $table->dropUnique('unique_etudiant_par_semestre');
            $table->dropColumn(['annee_academique', 'semestre']);
            $table->unique('user_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Supprimer la table existante
        Schema::dropIfExists('classe_user');

        // Recréer la table avec la bonne structure
        Schema::create('classe_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('annee_academique', 50);
            $table->string('semestre', 20);
            $table->boolean('dropped')->default(false);
            $table->timestamps();

            // Contrainte unique qui permet un étudiant par semestre par année
            $table->unique(['user_id', 'annee_academique', 'semestre'], 'unique_etudiant_par_semestre');
        });

        // Trigger pour remplir automatiquement les colonnes année et semestre
        DB::unprepared('
            CREATE TRIGGER update_classe_user_details BEFORE INSERT ON classe_user
            FOR EACH ROW
            BEGIN
                SELECT annee_academique, semestre
                INTO @annee, @semestre
                FROM classes
                WHERE id = NEW.classe_id;

                SET NEW.annee_academique = @annee;
                SET NEW.semestre = @semestre;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_classe_user_details');
        Schema::dropIfExists('classe_user');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Supprimer les tables existantes si elles existent
        Schema::dropIfExists('classe_user');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('annee_academiques');

        // 1. Créer la table annee_academiques
        Schema::create('annee_academiques', function (Blueprint $table) {
            $table->id();
            $table->string('annee');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['en_cours', 'terminee'])->default('en_cours');
            $table->timestamps();
        });

        // 2. Créer la table classes
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('annee_academique_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('coordinateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('semestre')->default('1');
            $table->boolean('semestre_termine')->default(false);
            $table->integer('semestre_actuel')->default(1);
            $table->enum('statut', ['en_cours', 'terminee'])->default('en_cours');
            $table->timestamps();
        });

        // 3. Créer la table classe_user
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

        // 4. Créer le trigger pour remplir automatiquement année et semestre
        DB::unprepared('
            CREATE TRIGGER update_classe_user_details BEFORE INSERT ON classe_user
            FOR EACH ROW
            BEGIN
                SELECT aa.annee, c.semestre
                INTO @annee, @semestre
                FROM classes c
                JOIN annee_academiques aa ON aa.id = c.annee_academique_id
                WHERE c.id = NEW.classe_id;

                SET NEW.annee_academique = @annee;
                SET NEW.semestre = @semestre;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_classe_user_details');
        Schema::dropIfExists('classe_user');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('annee_academiques');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('classe_user', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte unique sur user_id
            $table->dropUnique(['user_id']);
        });

        // Recréer la contrainte unique qui inclut user_id et l'année académique/semestre via une sous-requête
        DB::statement('
            ALTER TABLE classe_user
            ADD CONSTRAINT unique_etudiant_par_semestre UNIQUE (
                user_id,
                (SELECT CONCAT(annee_academique, semestre) FROM classes WHERE classes.id = classe_user.classe_id)
            );
        ');
    }

    public function down()
    {
        DB::statement('ALTER TABLE classe_user DROP CONSTRAINT IF EXISTS unique_etudiant_par_semestre');

        Schema::table('classe_user', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }
};

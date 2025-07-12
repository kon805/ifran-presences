<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('classe_user', function (Blueprint $table) {
            // Ajouter une contrainte unique sur user_id pour qu'un étudiant ne puisse être que dans une seule classe
            $table->unique('user_id');
        });
    }

    public function down()
    {
        Schema::table('classe_user', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->primary(['classe_id', 'user_id']);
        });
    }
};

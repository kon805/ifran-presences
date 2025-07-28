<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer les tables existantes dans le bon ordre
        Schema::dropIfExists('classe_user');
        Schema::dropIfExists('classes');

        // Recréer la table classes
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('annee_academique');
            $table->enum('semestre', ['1', '2']);
            $table->enum('statut', ['en_cours', 'termine'])->default('en_cours');
            $table->foreignId('coordinateur_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Recréer la table pivot classe_user
        Schema::create('classe_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index(['classe_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('classe_user');
        Schema::dropIfExists('classes');
    }
};

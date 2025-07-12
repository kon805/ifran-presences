<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer d'abord la table
        Schema::dropIfExists('classe_user');

        // Recréer la table avec la bonne structure
        Schema::create('classe_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // On utilise un index composite standard à la place d'une contrainte complexe
            $table->index(['classe_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('classe_user');
    }
};

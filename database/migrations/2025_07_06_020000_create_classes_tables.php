<?php

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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('annee_academique_id')->nullable()->constrained()->onDelete('set null');
            $table->string('semestre')->default('1');
            $table->boolean('semestre_termine')->default(false);
            $table->integer('semestre_actuel')->default(1);
            $table->enum('statut', ['en_cours', 'termine'])->default('en_cours');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_user');
        Schema::dropIfExists('classes');
    }
};

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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
              $table->foreignId('classe_id')->constrained()->onDelete('cascade');
              $table->foreignId('professeur_id')->constrained('users')->onDelete('cascade');
              $table->string('matiere');
               $table->date('date');
              $table->time('heure_debut');
             $table->time('heure_fin');
              $table->enum('etat', ['programmé', 'annulé', 'reporté'])->default('programmé');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};

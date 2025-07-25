<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Création de la table type_cours uniquement si elle n'existe pas
        if (!Schema::hasTable('type_cours')) {
            Schema::create('type_cours', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('code')->unique(); // présentiel, e-learning, workshop
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Insertion des types par défaut
            DB::table('type_cours')->insert([
                [
                    'nom' => 'Cours en présentiel',
                    'code' => 'presentiel',
                    'description' => 'Cours dispensé en salle de classe',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nom' => 'Cours en e-learning',
                    'code' => 'e-learning',
                    'description' => 'Cours en ligne asynchrone',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nom' => 'Workshop',
                    'code' => 'workshop',
                    'description' => 'Atelier pratique',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Création de la table pivot cours_type uniquement si elle n'existe pas
        if (!Schema::hasTable('cours_type')) {
            Schema::create('cours_type', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cours_id')->constrained()->onDelete('cascade');
                $table->foreignId('type_cours_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cours_type');
        Schema::dropIfExists('type_cours');
    }
};

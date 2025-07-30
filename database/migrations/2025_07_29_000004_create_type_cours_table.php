<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTypeCoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // Insérer les types de cours par défaut
        DB::table('type_cours')->insert([
            [
                'nom' => 'Présentiel',
                'code' => 'presentiel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'E-learning',
                'code' => 'e-learning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Workshop',
                'code' => 'workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_cours');
    }
}

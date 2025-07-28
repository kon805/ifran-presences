<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->unsignedBigInteger('matiere_id')->nullable()->after('classe_id');
            $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('set null');
            $table->dropColumn('matiere');
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->string('matiere')->after('classe_id');
            $table->dropForeign(['matiere_id']);
            $table->dropColumn('matiere_id');
        });
    }
};

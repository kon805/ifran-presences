<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->boolean('semestre_termine')->default(false);
            $table->integer('semestre_actuel')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('semestre_termine');
            $table->dropColumn('semestre_actuel');
        });
    }
};

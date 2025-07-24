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
        Schema::table('classe_user', function (Blueprint $table) {
            if (!Schema::hasColumn('classe_user', 'dropped')) {
                $table->boolean('dropped')->default(false)->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classe_user', function (Blueprint $table) {
            if (Schema::hasColumn('classe_user', 'dropped')) {
                $table->dropColumn('dropped');
            }
        });
    }
};

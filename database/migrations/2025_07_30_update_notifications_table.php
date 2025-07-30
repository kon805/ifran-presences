<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'id')) {
                    $table->uuid('id')->primary();
                }
                if (!Schema::hasColumn('notifications', 'type')) {
                    $table->string('type');
                }
                if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                    $table->string('notifiable_type');
                }
                if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                    $table->string('notifiable_id');
                }
                if (!Schema::hasColumn('notifications', 'data')) {
                    $table->text('data');
                }
                if (!Schema::hasColumn('notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable();
                }
            });
        }
    }

    public function down()
    {
        // Ne rien faire en cas de rollback
    }
}

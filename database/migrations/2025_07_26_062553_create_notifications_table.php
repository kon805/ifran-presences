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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'absence' ou 'dropped'
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('destinataire_id')->constrained('users')->onDelete('cascade'); // ID du parent ou coordinateur
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->json('details')->nullable(); // Stockage des détails supplémentaires
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['destinataire_id', 'lu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

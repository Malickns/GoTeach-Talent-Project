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
          Schema::create('documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->foreignId('jeune_id')
                ->constrained('jeunes', 'jeune_id')
                ->onDelete('cascade');
            $table->enum('type', ['cv', 'lettre_motivation']);
            $table->string('chemin_fichier');
            $table->string('nom_original')->nullable();
            $table->timestamp('date_upload')->useCurrent();
            $table->timestamps();
            $table->unique(['jeune_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

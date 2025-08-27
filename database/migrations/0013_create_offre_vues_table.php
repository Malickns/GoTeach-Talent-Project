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
        Schema::create('offre_vues', function (Blueprint $table) {
            $table->id('vue_id');
            $table->foreignId('offre_id')->constrained('offres_emploi', 'offre_id')->onDelete('cascade');
            $table->foreignId('jeune_id')->nullable()->constrained('jeunes', 'jeune_id')->onDelete('set null');
            $table->timestamp('vue_le')->useCurrent();
            $table->timestamps();
            $table->index(['offre_id']);
            $table->index(['jeune_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offre_vues');
    }
};



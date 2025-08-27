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
        Schema::create('categories_jeunes', function (Blueprint $table) {
            $table->id('categorie_id');
            $table->foreignId('programme_id')->constrained('programmes', 'programme_id')->onDelete('cascade');
            $table->string('nom', 50);
            $table->text('description')->nullable();
            $table->boolean('statut')->default(true);
            $table->timestamps();
            $table->unique(['programme_id', 'nom']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_jeunes');
    }
};

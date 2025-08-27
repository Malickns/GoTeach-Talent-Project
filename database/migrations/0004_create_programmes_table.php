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
         Schema::create('programmes', function (Blueprint $table) {
    $table->id('programme_id');
    $table->string('nom', 50)->unique();
    $table->text('description')->nullable();
    $table->timestamp('date_creation')->useCurrent();
    $table->foreignId('responsable_id')->nullable()
        ->constrained('users', 'user_id')
        ->onDelete('set null');
    $table->boolean('statut')->default(true);
    $table->timestamps();
    $table->softDeletes(); // Ajoute deleted_at pour SoftDeletes
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};

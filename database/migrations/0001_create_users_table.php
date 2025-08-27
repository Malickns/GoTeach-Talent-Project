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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            
            // Informations personnelles
            $table->string('prenom', 50);
            $table->string('nom', 50);
            $table->text('photo_profil')->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('telephone', 20)->nullable();
            
            // Rôle et statut
            $table->enum('role', ['jeune', 'employeur', 'admin_local', 'admin_national']);
            $table->enum('statut', ['inactif', 'actif', 'suspendu', 'supprime'])->default('inactif');
            
            // Traçabilité de validation
            $table->foreignId('valide_par')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            $table->timestamp('valide_le')->nullable();
            $table->softDeletes();
            
            // Sécurité et session
            $table->boolean('est_en_ligne')->default(false);
            $table->timestamp('derniere_activite')->nullable();
            $table->string('session_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            
          
            // Timestamps
            $table->timestamps();

            
            // Index pour performance
            $table->index(['role', 'statut']);
            $table->index(['email']);
            $table->index(['valide_par']);
        });

        

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

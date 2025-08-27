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
        Schema::create('employeurs', function (Blueprint $table) {
            $table->id('employeur_id');
            
            // Référence à l'user
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            
            // Informations de l'entreprise
            $table->string('nom_entreprise', 100);
            $table->string('raison_sociale', 100)->nullable();
            $table->string('numero_rccm', 50)->nullable()->unique();
            $table->string('numero_ninea', 50)->nullable()->unique();
            
            // Secteur d'activité
            $table->string('secteur_activite', 100)->nullable();
            $table->text('description_activite')->nullable();
            
            // Adresse de l'entreprise
            $table->string('adresse_entreprise', 255);
            $table->string('ville_entreprise', 100);
            $table->string('region_entreprise', 100);
            $table->string('pays_entreprise', 50)->default('Sénégal');
              
            // Contact entreprise
            $table->string('telephone_entreprise', 20)->nullable();
            $table->string('fax_entreprise', 20)->nullable();
            $table->string('site_web', 255)->nullable();
            $table->string('email_entreprise', 100)->nullable();
            
            // Informations financières
            $table->enum('type_entreprise', ['privee', 'publique', 'ong', 'cooperative', 'autre']);
            
           
            // Statistiques
            $table->integer('nombre_offres_publiees')->default(0);
            $table->integer('nombre_candidatures_recues')->default(0);
            $table->integer('nombre_embauches_effectuees')->default(0);
            
           
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['user_id']);
            $table->index(['secteur_activite']);
            $table->index(['ville_entreprise', 'region_entreprise']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeurs');
    }
};

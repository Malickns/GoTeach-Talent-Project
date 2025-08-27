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
        Schema::create('offres_emploi', function (Blueprint $table) {
            $table->id('offre_id');
            
            // Références
            $table->foreignId('employeur_id')->constrained('employeurs', 'employeur_id')->onDelete('cascade');
            
            // Informations de base
            $table->string('titre', 100);
            $table->text('description')->nullable();
            $table->text('missions_principales')->nullable();
            // Type de contrat et conditions
            $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'formation', 'freelance', 'autre']);
            $table->integer('duree_contrat_mois')->nullable(); // Pour CDD
            $table->date('date_debut_contrat')->nullable();
            $table->date('date_fin_contrat')->nullable();
            
            // Rémunération
            $table->string('grade', 50)->nullable(); // Pour les offres de la fonction publique
            
        
            // Localisation
            $table->string('ville_travail', 100);
            
            
            
            // Exigences
            $table->json('competences_requises')->nullable();
            
           
            // Dates importantes
            $table->timestamp('date_publication')->useCurrent();
            $table->timestamp('date_expiration')->nullable();
            
            
            // Visibilité et promotion
            $table->boolean('offre_urgente')->default(false);
            $table->integer('nombre_vues')->default(0); // Compteur global (les vues détaillées seront dans une table séparée)
            $table->integer('nombre_candidatures')->default(0);
            
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['employeur_id']);
            $table->index(['type_contrat']);
            $table->index(['ville_travail']);
            $table->index(['date_expiration']);
            $table->index(['offre_urgente',]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres_emploi');
    }
};

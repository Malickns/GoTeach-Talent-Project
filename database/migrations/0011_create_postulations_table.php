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
        Schema::create('postulations', function (Blueprint $table) {
            $table->id('postulation_id');
            
            // Références
            $table->foreignId('offre_id')->constrained('offres_emploi', 'offre_id')->onDelete('cascade');
            $table->foreignId('jeune_id')->constrained('jeunes', 'jeune_id')->onDelete('cascade');
            
            // Documents de candidature
            $table->string('cv_path')->nullable(); // Peut être null si pris depuis bibliothèque
            $table->string('lettre_motivation_path')->nullable(); // Peut être null si pris depuis bibliothèque
            $table->foreignId('cv_document_id')->nullable()->constrained('documents', 'document_id')->onDelete('set null');
            $table->foreignId('lm_document_id')->nullable()->constrained('documents', 'document_id')->onDelete('set null');
            $table->json('documents_supplementaires')->nullable(); // [{'nom': 'certificat', 'path': '...'}]
            
            // Statut de la candidature
            $table->enum('statut', [
                'en_attente', 'en_revue', 'retenu', 'rejete', 'entretien_programme', 
                'entretien_effectue', 'embauche', 'refuse_apres_entretien', 'retiree'
            ])->default('en_attente');
            
            // Feedback et évaluation
            $table->text('commentaire_employeur')->nullable();
            $table->text('commentaire_jeune')->nullable();
            $table->text('raison_rejet')->nullable();
            $table->text('feedback_entretien')->nullable();
            
            // Dates importantes
            $table->timestamp('date_postulation')->useCurrent();
            $table->timestamp('date_revue')->nullable();
            $table->timestamp('date_decision')->nullable();
            $table->timestamp('date_retrait')->nullable();
            
            // Informations de suivi
            $table->boolean('notifie_employeur')->default(false);
            $table->boolean('notifie_jeune')->default(false);
            $table->boolean('notifie_admin')->default(false);
            $table->timestamp('derniere_notification')->nullable();
            
       
    
            // Statistiques
            $table->integer('nombre_vues_employeur')->default(0);
            $table->integer('nombre_telechargements_cv')->default(0);
            $table->integer('nombre_telechargements_lm')->default(0);
            
           
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['offre_id']);
            $table->index(['jeune_id']);
            $table->index(['statut']);
            $table->index(['date_postulation']);
            $table->index(['cv_document_id']);
            $table->index(['lm_document_id']);
            
            // Index composite pour éviter les doublons
            $table->unique(['offre_id', 'jeune_id'], 'unique_postulation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulations');
    }
};

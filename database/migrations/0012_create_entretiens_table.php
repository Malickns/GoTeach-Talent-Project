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
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id('entretien_id');
            
            // Références
            $table->foreignId('postulation_id')->constrained('postulations', 'postulation_id')->onDelete('cascade');
            $table->foreignId('programme_par')->nullable()->constrained('users', 'user_id')->onDelete('set null'); // Qui a programmé
            
            // Informations de base
            $table->dateTime('date_heure');
            $table->integer('duree_minutes')->default(60);
            $table->enum('type_entretien', ['presentiel', 'visioconference', 'telephonique', 'hybride']);
            
            // Localisation
            $table->string('lieu')->nullable(); // Lieu physique
            $table->string('lien_visio')->nullable(); // Lien visioconférence
            $table->string('numero_telephone')->nullable(); // Pour entretien téléphonique
            $table->text('instructions_participants')->nullable();
            
            // Statut et suivi
            $table->enum('statut', ['prévu', 'confirmé', 'effectué', 'annulé', 'reporté'])->default('prévu');
            $table->enum('participation_candidat', ['en_attente', 'confirmé', 'annulé', 'absent'])->default('en_attente');
            
            
           
            // Notifications et rappels
            $table->boolean('notifie_candidat')->default(false);
            $table->boolean('notifie_employeur')->default(false);
            $table->boolean('notifie_admin')->default(false);
            $table->timestamp('rappel_24h_envoye')->nullable();
            $table->timestamp('rappel_1h_envoye')->nullable();
            $table->timestamp('rappel_48h_envoye')->nullable();
            
            
            
            // Automatisation
            $table->boolean('auto_annulation_48h')->default(true);
            $table->timestamp('deadline_confirmation')->nullable(); // 48h après programmation
           
            // Statistiques
            $table->integer('nombre_rappels_envoyes')->default(0);
            $table->integer('nombre_participants_effectifs')->default(0);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['postulation_id']);
            $table->index(['programme_par']);
            $table->index(['statut']);
            $table->index(['date_heure']);
            $table->index(['participation_candidat']);
            $table->index(['type_entretien']);
            $table->index(['deadline_confirmation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};

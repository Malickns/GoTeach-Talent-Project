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
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            
            // Référence à l'user
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            
            // Informations administratives
            $table->enum('niveau_admin', ['local', 'national']);
            $table->string('fonction', 100);
            
            // Zone de responsabilité (pour admin local)
            $table->foreignId('programme_id')->nullable()->constrained('programmes', 'programme_id')->onDelete('set null');
            
            
            // Permissions spécifiques
            $table->boolean('peut_creer_jeunes')->default(true);
            $table->boolean('peut_creer_employeurs')->default(true);
            $table->boolean('peut_creer_admins')->default(false); // Seul admin national
            $table->boolean('peut_valider_offres')->default(true);
            $table->boolean('peut_voir_statistiques')->default(true);
            $table->boolean('peut_gerer_documents')->default(true);
            $table->boolean('peut_modifier_programmes')->default(false); // Seul admin national
            $table->boolean('peut_supprimer_comptes')->default(true);
            $table->boolean('peut_voir_historique')->default(true);
            
            // Contact professionnel
            $table->string('telephone_bureau', 20)->nullable();
            $table->string('email_bureau', 100)->nullable();
            $table->string('bureau_localisation', 100)->nullable();
            
            
            // Statistiques d'activité
            $table->integer('nombre_jeunes_crees')->default(0);
            $table->integer('nombre_employeurs_crees')->default(0);
            $table->integer('nombre_offres_validees')->default(0);
            $table->integer('nombre_actions_effectuees')->default(0);
            
            // Paramètres de notification
            $table->boolean('notifications_nouvelles_inscriptions')->default(true);
            $table->boolean('notifications_nouvelles_offres')->default(true);
            $table->boolean('notifications_candidatures')->default(true);
            
            
            // Statut administratif
            $table->enum('statut_administratif', ['actif', 'inactif', 'en_conge', 'suspendu'])->default('actif');
            $table->foreignId('modifie_par')->nullable()->constrained('admins', 'admin_id')->onDelete('set null');
            $table->timestamp('modifie_le')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['user_id']);
            $table->index(['niveau_admin']);
            $table->index(['programme_id']);
            $table->index(['statut_administratif']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};

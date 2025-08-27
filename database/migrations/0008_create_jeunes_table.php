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
        Schema::create('jeunes', function (Blueprint $table) {
            $table->id('jeune_id');
            
            // Référence à l'user
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            
            // Informations personnelles
            $table->date('date_naissance');
            $table->enum('genre', ['homme', 'femme']);
            $table->string('lieu_naissance', 100);
            $table->string('numero_cni', 50)->nullable()->unique();
            
            // Adresse
            $table->string('adresse', 255);
            $table->string('ville', 100);
            $table->string('region', 100);
            $table->string('pays', 50)->default('Sénégal');
            
            // Éducation et formation
            $table->string('niveau_etude', 50);
            $table->string('dernier_diplome', 100)->nullable();
            $table->string('etablissement', 100)->nullable();
            $table->integer('annee_obtention')->nullable();
            
            // Expérience professionnelle
            $table->text('experience_professionnelle')->nullable();
            $table->text('competences')->nullable();
            $table->text('langues_parlees')->nullable();
            
            // Situation actuelle
            $table->string('situation_actuelle', 50)->nullable();
            $table->text('disponibilite')->nullable();
            $table->text('preferences_emploi')->nullable();
            
            // Informations SOS Village
            $table->foreignId('programme_id')->nullable()->constrained('programmes', 'programme_id')->onDelete('set null');
            $table->foreignId('categorie_id')->nullable()->constrained('categories_jeunes', 'categorie_id')->onDelete('set null');

            
            // Statistiques et suivi
            $table->integer('nombre_candidatures')->default(0);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['user_id']);
            $table->index(['programme_id']);
            $table->index(['categorie_id']);
            $table->index(['ville', 'region']);
            $table->index(['niveau_etude']);
            $table->index(['genre']);
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jeunes');
    }
};

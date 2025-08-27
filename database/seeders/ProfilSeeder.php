<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Profil du jeune - Adama Ndiaye
        DB::table('jeunes')->insert([
                'jeune_id' => 1,
                'user_id' => 1,
                'date_naissance' => '2000-05-15',
                'genre' => 'homme',
                'lieu_naissance' => 'Dakar',
                'nationalite' => 'Sénégalaise',
                'numero_cni' => 'DN-123456789',
                'adresse' => 'Quartier Médina, Dakar',
                'ville' => 'Dakar',
                'region' => 'Dakar',
                'pays' => 'Sénégal',
                'niveau_etude' => 'Bac + 2',
                'dernier_diplome' => 'BTS en Informatique',
                'etablissement' => 'Institut Supérieur de Technologie',
                'annee_obtention' => 2022,
                'experience_professionnelle' => 'Stage de 6 mois en développement web',
                'competences' => 'PHP, Laravel, JavaScript, HTML/CSS, MySQL',
                'langues_parlees' => 'Français, Wolof, Anglais (niveau intermédiaire)',
                'situation_actuelle' => 'En recherche d\'emploi',
                'disponibilite' => 'Immédiate',
                'preferences_emploi' => 'Développement web, informatique',
                'programme_id' => 1, // Programme de Dakar
                'date_entree_sos' => '2015-03-10',
                'date_sortie_sos' => '2020-06-15',
                'contact_urgence_nom' => 'Mariama Ndiaye',
                'contact_urgence_telephone' => '+221 77 987 65 43',
                'contact_urgence_relation' => 'Mère',
                'secteurs_interet' => json_encode(['informatique', 'développement', 'web']),
                'zones_geographiques' => json_encode(['Dakar', 'Thies']),
                'type_contrat_souhaite' => 'CDI',
                'nombre_candidatures' => 0,
                'nombre_entretiens' => 0,
                'nombre_emplois_obtenus' => 0,
                'profil_public' => true,
                'recevoir_offres' => true,
                'partager_avec_employeurs' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        // Profil de l'employeur - Fama Seck
        DB::table('employeurs')->insert([
                'employeur_id' => 1,
                'user_id' => 2,
                'nom_entreprise' => 'Tech Solutions Sénégal',
                'raison_sociale' => 'Tech Solutions Sénégal SARL',
                'numero_rccm' => 'SN DKR 2023 B 12345',
                'numero_ninea' => 'SN123456789',
                'numero_contribuable' => 'SN987654321',
                'secteur_activite' => 'Informatique et Technologies',
                'description_activite' => 'Développement de solutions informatiques, sites web, applications mobiles',
                'taille_entreprise' => 'moyenne',
                'nombre_employes' => 25,
                'nombre_employes_actuel' => 20,
                'adresse_entreprise' => 'Zone 4, Almadies, Dakar',
                'ville_entreprise' => 'Dakar',
                'region_entreprise' => 'Dakar',
                'pays_entreprise' => 'Sénégal',
                'code_postal_entreprise' => '11000',
                'telephone_entreprise' => '+221 33 123 45 67',
                'site_web' => 'https://techsolutions.sn',
                'email_entreprise' => 'contact@techsolutions.sn',
                'contact_rh_nom' => 'Fatou Diop',
                'contact_rh_telephone' => '+221 77 234 56 78',
                'contact_rh_email' => 'rh@techsolutions.sn',
                'type_entreprise' => 'privee',
                'autorise_publier_offres' => true,
                'autorise_voir_candidatures' => true,
                'autorise_contacter_jeunes' => true,
                'profil_public' => true,
                'nombre_offres_publiees' => 0,
                'nombre_candidatures_recues' => 0,
                'nombre_embauches_effectuees' => 0,
                'statut_verification' => 'verifie',
                'verifie_par' => 3, // Validé par Cheikh Faye
                'verifie_le' => now(),
                 'created_at' => now(),
                'updated_at' => now(),
            ]);

        // Profil admin national - Aminata Gaye
        DB::table('admins')->insert([
                'admin_id' => 2,
                'user_id' => 4, // Correspond à user_id 4 (Aminata Gaye)
                'niveau_admin' => 'national',
                'matricule_admin' => 'ADM-NAT-001',
                'fonction' => 'Directrice Nationale Insertion',
                'departement' => 'Direction Nationale',
                'programme_id' => null, // Admin national - tous les programmes
                'regions_responsabilite' => json_encode(['Dakar', 'Thies', 'Louga', 'Kaolack', 'Ziguinchor', 'Tambacounda']),
                'villes_responsabilite' => json_encode(['Dakar', 'Thies', 'Louga', 'Kaolack', 'Ziguinchor', 'Tambacounda']),
                'peut_creer_jeunes' => true,
                'peut_creer_employeurs' => true,
                'peut_creer_admins' => true,
                'peut_valider_offres' => true,
                'peut_voir_statistiques' => true,
                'peut_gerer_documents' => true,
                'peut_modifier_programmes' => true,
                'peut_supprimer_comptes' => true,
                'peut_voir_historique' => true,
                'telephone_bureau' => '+221 33 456 78 90',
                'email_bureau' => 'aminata.gaye@sosvillage.sn',
                'bureau_localisation' => 'Siège National Dakar',
                'superviseur_id' => null, // Admin national - pas de superviseur
                'date_nomination' => '2022-06-01',
                'motif_nomination' => 'Directrice nationale avec 10 ans d\'expérience',
                'nombre_jeunes_crees' => 0,
                'nombre_employeurs_crees' => 0,
                'nombre_offres_validees' => 0,
                'nombre_actions_effectuees' => 0,
                'notifications_nouvelles_inscriptions' => true,
                'notifications_nouvelles_offres' => true,
                'notifications_candidatures' => true,
                'notifications_entretiens' => true,
                'notifications_alertes' => true,
                'notes_administratives' => 'Directrice nationale responsable de tous les programmes',
                'actions_recentes' => json_encode([]),
                'statut_administratif' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('admins')->insert([
            'admin_id' => 4, // Nouvel ID différent de l'admin national
            'user_id' => 3,
            'niveau_admin' => 'local',
            'matricule_admin' => 'ADM-LOC-001',
            'fonction' => 'Coordinateur Régional Tambacounda',
            'departement' => 'Programme Tambacounda',
            'programme_id' => 1, // Programme Tambacounda
            'regions_responsabilite' => json_encode(['Tambacounda']),
            'villes_responsabilite' => json_encode(['Tambacounda']),
            'peut_creer_jeunes' => true,
            'peut_creer_employeurs' => true,
            'peut_creer_admins' => false, // Un admin local ne peut pas créer d'autres admins
            'peut_valider_offres' => true,
            'peut_voir_statistiques' => true,
            'peut_gerer_documents' => true,
            'peut_modifier_programmes' => false, // Réservé aux admins nationaux
            'peut_supprimer_comptes' => true,
            'peut_voir_historique' => true,
            'telephone_bureau' => '+221 33 345 67 89',
            'email_bureau' => 'cheikh.faye@sosvillage.sn',
            'bureau_localisation' => 'SOS Village Tambacounda',
            'superviseur_id' => 2, // Supervisé par l'admin national (Aminata Gaye)
            'date_nomination' => '2023-01-15',
            'motif_nomination' => 'Coordinateur expérimenté avec 5 ans dans le programme',
            'nombre_jeunes_crees' => 0,
            'nombre_employeurs_crees' => 0,
            'nombre_offres_validees' => 0,
            'nombre_actions_effectuees' => 0,
            'notifications_nouvelles_inscriptions' => true,
            'notifications_nouvelles_offres' => true,
            'notifications_candidatures' => true,
            'notifications_entretiens' => true,
            'notifications_alertes' => true,
            'notes_administratives' => 'Responsable du programme Tambacounda',
            'actions_recentes' => json_encode([]),
            'statut_administratif' => 'actif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 
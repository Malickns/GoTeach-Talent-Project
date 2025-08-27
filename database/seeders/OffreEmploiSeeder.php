<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OffreEmplois;
use App\Models\Employeur;
use App\Models\User;

class OffreEmploiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un employeur existant ou en créer un
        $employeur = Employeur::first();
        
        if (!$employeur) {
            // Créer un utilisateur employeur
            $user = User::create([
                'prenom' => 'Fama',
                'nom' => 'Seck',
                'email' => 'fama@employeur.com',
                'password' => bcrypt('password'),
                'telephone' => '0123456789',
                'role' => 'employeur',
                'statut' => 'actif',
            ]);

            $employeur = Employeur::create([
                'user_id' => $user->user_id,
                'nom_entreprise' => 'Tech Solutions SARL',
                'secteur_activite' => 'Informatique',
                'adresse_entreprise' => '123 Rue de la Tech',
                'ville_entreprise' => 'Paris',
                'pays_entreprise' => 'France',
                'telephone_entreprise' => '0123456789',
                'site_web' => 'https://techsolutions.fr',
                'description_entreprise' => 'Entreprise spécialisée dans le développement web',
                'statut' => 'actif',
            ]);
        }

        // Créer des offres d'emplois de test
        $offres = [
            [
                'titre' => 'Développeur Full Stack Laravel',
                'description' => 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets web innovants utilisant Laravel, Vue.js et MySQL.',
                'profil_recherche' => 'Profil idéal : Développeur avec 3+ ans d\'expérience en Laravel, Vue.js et MySQL. Autonome, curieux et passionné par les nouvelles technologies.',
                'missions_principales' => '- Développement d\'applications web avec Laravel et Vue.js
- Maintenance et évolution des applications existantes
- Participation aux réunions d\'équipe et aux revues de code
- Collaboration avec les designers et les chefs de projet',
                'avantages_offerts' => '- Télétravail possible 2 jours par semaine
- Mutuelle santé
- Tickets restaurant
- Évolution de carrière possible',
                'type_contrat' => 'cdi',
                'ville_travail' => 'Paris',
                'offre_urgente' => true,
            ],
            [
                'titre' => 'Stagiaire Développeur Frontend',
                'description' => 'Stage de 6 mois pour un étudiant en développement web. Vous participerez au développement d\'interfaces utilisateur modernes et responsives.',
                'profil_recherche' => 'Étudiant en informatique (Bac+3/Bac+4) avec des connaissances en HTML, CSS, JavaScript. Curieux et motivé.',
                'missions_principales' => '- Développement d\'interfaces utilisateur avec React
- Intégration de maquettes en HTML/CSS
- Tests et débogage
- Documentation technique',
               
                'type_contrat' => 'stage',
                'ville_travail' => 'Lyon',
                
                'competences_requises' => ['HTML', 'CSS', 'JavaScript', 'React'],
               
                'offre_urgente' => false,
            
            ],
            [
                'titre' => 'DevOps Engineer',
                'description' => 'Nous cherchons un ingénieur DevOps pour optimiser nos infrastructures cloud et automatiser nos processus de déploiement.',
                'profil_recherche' => 'Ingénieur avec 5+ ans d\'expérience en DevOps, maîtrise d\'AWS/Azure, Docker, Kubernetes. Autonome et force de proposition.',
                'missions_principales' => '- Gestion et optimisation des infrastructures cloud
- Automatisation des déploiements avec CI/CD
- Monitoring et alerting
- Sécurisation des environnements',
               
                'type_contrat' => 'cdi',
                'ville_travail' => 'Marseille',
                'niveau_etude_minimum' => 'Bac+5',
                
                'offre_urgente' => true,
              
            ],
        ];

        foreach ($offres as $offreData) {
            $offreData['employeur_id'] = $employeur->employeur_id;
            OffreEmplois::create($offreData);
        }

        $this->command->info('Offres d\'emplois créées avec succès !');
    }
} 
# Dashboard Jeune - GOTeach

## 📋 Vue d'ensemble

Le dashboard jeune est une interface complète permettant aux jeunes de rechercher des offres d'emploi, de postuler et de gérer leurs candidatures. Il offre une expérience utilisateur moderne et intuitive pour faciliter la recherche d'emploi.

## 🚀 Fonctionnalités Principales

### 1. **Dashboard Principal**
- **Statistiques personnelles** : Nombre de candidatures, entretiens, emplois obtenus
- **Offres recommandées** : Suggestions basées sur le profil et les préférences
- **Offres récentes** : Dernières offres publiées
- **Candidatures récentes** : Suivi des dernières candidatures
- **Conseils personnalisés** : Astuces pour améliorer les chances d'embauche

### 2. **Recherche d'Offres**
- **Filtres avancés** : Secteur, ville, type de contrat
- **Recherche textuelle** : Par mots-clés dans le titre et la description
- **Pagination** : Navigation facile entre les pages
- **Tri par pertinence** : Offres les plus adaptées en premier

### 3. **Système de Candidature**
- **Upload de documents** : CV et lettre de motivation obligatoires
- **Message personnalisé** : Possibilité d'ajouter un message à l'employeur
- **Validation automatique** : Vérification des formats et tailles de fichiers
- **Suivi des statuts** : En attente, retenue, rejetée, embauché

### 4. **Gestion des Documents**
- **Upload multiple** : CV, lettres de motivation, diplômes, certificats
- **Organisation** : Documents classés par type
- **Sécurité** : Accès restreint aux documents personnels
- **Téléchargement** : Possibilité de récupérer ses documents

### 5. **Suivi des Candidatures**
- **Historique complet** : Toutes les candidatures avec statuts
- **Détails des offres** : Informations complètes sur chaque postulation
- **Annulation** : Possibilité d'annuler les candidatures en attente
- **Notifications** : Alertes pour les nouvelles réponses

## 🛠️ Architecture Technique

### **Modèles Eloquent**

#### `Jeune` Model
```php
// Relations principales
public function user() // Relation avec l'utilisateur
public function programme() // Programme SOS
public function postulations() // Candidatures
public function documents() // Documents personnels

// Méthodes utilitaires
public function hasCv() // Vérifier si CV existe
public function hasLettreMotivation() // Vérifier si LM existe
public function hasPostule($offreId) // Vérifier si déjà postulé
public function getOffresRecommandees() // Offres personnalisées
public function updateStatistiques() // Mettre à jour les stats
```

#### `Document` Model
```php
// Types de documents supportés
'cv', 'lettre_motivation', 'diplome', 'certificat', 'photo'

// Fonctionnalités
public function telecharger() // Télécharger le fichier
public function supprimerFichier() // Supprimer physiquement
public function getUrlAttribute() // URL publique
public function getTailleFormateeAttribute() // Taille lisible
```

### **Contrôleur Principal**

#### `JeuneController`
- **Dashboard** : Affichage des statistiques et offres recommandées
- **Offres** : Liste et filtrage des offres d'emploi
- **Candidature** : Processus de postulation complet
- **Documents** : Gestion des fichiers personnels
- **Suivi** : Historique et statuts des candidatures

## 📁 Structure des Fichiers

```
resources/views/pages/jeunes/
├── dashboard.blade.php          # Dashboard principal
├── offres.blade.php            # Liste des offres
├── show-offre.blade.php        # Détail d'une offre
├── candidature.blade.php       # Formulaire de candidature
├── candidatures.blade.php      # Liste des candidatures
├── show-candidature.blade.php  # Détail d'une candidature
└── documents.blade.php         # Gestion des documents
```

## 🔧 Configuration

### **Routes**
```php
// Routes principales
Route::get('/jeunes/dashboard', [JeuneController::class, 'dashboard']);
Route::get('/jeunes/offres', [JeuneController::class, 'offres']);
Route::get('/jeunes/offres/{offre}', [JeuneController::class, 'showOffre']);
Route::post('/jeunes/offres/{offre}/postuler', [JeuneController::class, 'postuler']);
Route::get('/jeunes/candidatures', [JeuneController::class, 'candidatures']);
Route::get('/jeunes/documents', [JeuneController::class, 'documents']);
```

### **Middleware**
- `auth` : Authentification requise
- `role:jeune` : Rôle jeune uniquement
- `status:actif` : Compte actif uniquement

## 🎨 Interface Utilisateur

### **Design System**
- **Couleurs** : Palette cohérente avec l'identité GOTeach
- **Typographie** : Segoe UI pour une lecture optimale
- **Icônes** : Font Awesome pour une expérience moderne
- **Responsive** : Adaptation mobile et tablette

### **Composants UI**
- **Cartes interactives** : Hover effects et animations
- **Badges de statut** : Couleurs distinctives par statut
- **Boutons d'action** : Gradients et ombres
- **Formulaires** : Validation en temps réel

## 📊 Fonctionnalités Avancées

### **Système de Recommandations**
```php
// Algorithme de recommandation basé sur :
- Secteurs d'intérêt du jeune
- Zones géographiques préférées
- Type de contrat souhaité
- Niveau d'études
- Expérience professionnelle
```

### **Gestion des Documents**
- **Formats supportés** : PDF, DOC, DOCX, JPG, PNG
- **Taille maximale** : 2MB par fichier
- **Stockage sécurisé** : Dossier `storage/app/public/documents/`
- **Organisation** : Sous-dossiers par type de document

### **Système de Notifications**
- **Notifications toast** : Messages de succès/erreur
- **Badge de notifications** : Nombre de candidatures en attente
- **Alertes temps réel** : Mises à jour automatiques

## 🔒 Sécurité

### **Validation des Données**
```php
// Validation des candidatures
'cv' => 'required|file|mimes:pdf,doc,docx|max:2048'
'lettre_motivation' => 'required|file|mimes:pdf,doc,docx|max:2048'
'message_employeur' => 'nullable|string|max:1000'
```

### **Contrôle d'Accès**
- Vérification de l'appartenance des documents
- Protection contre les candidatures multiples
- Validation des statuts avant annulation

### **Sécurité des Fichiers**
- Validation des types MIME
- Limitation de la taille des fichiers
- Stockage dans des dossiers sécurisés

## 📈 Performance

### **Optimisations**
- **Eager Loading** : Relations chargées en une requête
- **Pagination** : Limitation du nombre d'éléments
- **Cache** : Mise en cache des statistiques
- **Indexation** : Index sur les colonnes fréquemment utilisées

### **Monitoring**
- **Logs d'activité** : Suivi des actions utilisateur
- **Métriques** : Temps de chargement et erreurs
- **Alertes** : Notifications en cas de problème

## 🚀 Déploiement

### **Prérequis**
```bash
# Installation des dépendances
composer install
npm install

# Configuration de la base de données
php artisan migrate

# Création du lien symbolique pour le stockage
php artisan storage:link

# Compilation des assets
npm run build
```

### **Variables d'Environnement**
```env
# Configuration du stockage
FILESYSTEM_DISK=public

# Limites de fichiers
UPLOAD_MAX_FILESIZE=2M
POST_MAX_SIZE=8M
```

## 🔄 Maintenance

### **Tâches Cron**
```bash
# Nettoyage des fichiers orphelins
php artisan jeunes:cleanup-documents

# Mise à jour des statistiques
php artisan jeunes:update-statistics

# Envoi des notifications
php artisan jeunes:send-notifications
```

### **Sauvegarde**
- **Documents** : Sauvegarde quotidienne du dossier `storage/app/public/documents/`
- **Base de données** : Sauvegarde automatique des tables jeunes et documents
- **Logs** : Rotation automatique des fichiers de log

## 📚 Documentation API

### **Endpoints Principaux**

#### `GET /jeunes/dashboard`
Affiche le dashboard principal avec statistiques et offres recommandées.

#### `GET /jeunes/offres`
Liste des offres d'emploi avec filtres.

#### `POST /jeunes/offres/{offre}/postuler`
Soumet une candidature avec CV et lettre de motivation.

#### `GET /jeunes/candidatures`
Historique des candidatures du jeune.

#### `POST /jeunes/documents/upload`
Upload d'un nouveau document.

## 🎯 Roadmap

### **Fonctionnalités Futures**
- [ ] Système de favoris pour les offres
- [ ] Notifications push en temps réel
- [ ] Chat avec les employeurs
- [ ] Évaluation des employeurs
- [ ] Système de recommandations avancé
- [ ] Intégration LinkedIn
- [ ] CV builder intégré
- [ ] Suivi des entretiens

### **Améliorations Techniques**
- [ ] API REST complète
- [ ] Tests automatisés
- [ ] Monitoring avancé
- [ ] Cache Redis
- [ ] CDN pour les documents
- [ ] Compression des images

## 🤝 Contribution

### **Guidelines**
1. Respecter les conventions de nommage Laravel
2. Ajouter des commentaires pour les méthodes complexes
3. Tester les nouvelles fonctionnalités
4. Documenter les changements

### **Structure des Tests**
```php
// Tests unitaires
tests/Unit/JeuneTest.php
tests/Unit/DocumentTest.php

// Tests d'intégration
tests/Feature/JeuneControllerTest.php
tests/Feature/CandidatureTest.php
```

## 📞 Support

Pour toute question ou problème :
- **Email** : support@goteach.sn
- **Documentation** : docs.goteach.sn
- **Issues** : GitHub repository

---

**Version** : 1.0.0  
**Dernière mise à jour** : {{ date('d/m/Y') }}  
**Auteur** : Équipe GOTeach 
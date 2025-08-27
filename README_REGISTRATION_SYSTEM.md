# 🚀 Système d'Inscription GoTeach - Documentation Complète

## 📋 Vue d'ensemble

J'ai créé un système d'inscription complet et sécurisé pour GoTeach qui respecte vos exigences métier. Le système permet uniquement l'inscription des jeunes et employeurs, avec un statut inactif par défaut nécessitant une validation administrative.

## 🎯 Fonctionnalités Implémentées

### ✅ **Types de Comptes Supportés**
- **Jeunes** : Accès aux opportunités d'emploi
- **Employeurs** : Publication d'offres d'emploi
- **Administrateurs** : Créés uniquement par l'admin national

### ✅ **Workflow d'Inscription**
1. **Inscription** → Statut "inactif"
2. **Validation** → Par admin local/national
3. **Activation** → Accès au dashboard

### ✅ **Sécurité Avancée**
- Protection CSRF
- Rate limiting (3 tentatives/heure)
- Validation stricte des données
- reCAPTCHA v2
- Hachage sécurisé des mots de passe
- Protection contre les attaques XSS/SQL Injection

## 🏗️ Architecture Technique

### **Modèles et Relations**

#### **User Model** (Table principale)
```php
- user_id (PK)
- prenom, nom, email, password, telephone
- role: jeune|employeur|admin_local|admin_national
- statut: inactif|actif|suspendu|supprime
- valide_par, valide_le, commentaire_validation
- email_verified_at, notifications_email, notifications_sms
```

#### **Jeune Model** (Profil jeune)
```php
- jeune_id (PK)
- user_id (FK)
- date_naissance, genre, lieu_naissance, nationalite
- adresse, ville, region, pays
- niveau_etude, dernier_diplome, etablissement
- programme_id (FK), categorie_id (FK)
- contact_urgence_*, secteurs_interet, zones_geographiques
- profil_public, recevoir_offres, partager_avec_employeurs
```

#### **Employeur Model** (Profil employeur)
```php
- employeur_id (PK)
- user_id (FK)
- nom_entreprise, raison_sociale, numero_rccm, numero_ninea
- secteur_activite, description_activite, taille_entreprise
- adresse_entreprise, ville_entreprise, region_entreprise
- contact_rh_*, type_entreprise
- autorise_publier_offres, statut_verification
```

### **Contrôleurs**

#### **RegistrationController**
- `showRegistrationForm()` : Affichage du formulaire
- `registerJeune()` : Traitement inscription jeune
- `registerEmployeur()` : Traitement inscription employeur
- `checkEmail()` : Vérification disponibilité email
- `checkRccm()` : Vérification disponibilité RCCM
- `checkNinea()` : Vérification disponibilité NINEA

### **Middleware de Sécurité**

#### **CheckAccountStatus**
```php
// Vérifie le statut du compte utilisateur
- inactif → Redirection vers login avec message
- suspendu → Redirection avec message de suspension
- supprime → Redirection avec message de suppression
- actif → Accès autorisé
```

### **Services**

#### **RecaptchaService**
```php
- verify($response, $remoteIp) : Validation reCAPTCHA
- verifyWithScore($response, $minScore) : Validation avec score
```

#### **Règle de Validation**
```php
- Recaptcha : Règle personnalisée pour reCAPTCHA
```

## 🔐 Mesures de Sécurité

### **1. Protection CSRF**
```php
@csrf // Token automatique dans tous les formulaires
```

### **2. Rate Limiting**
```php
// 3 tentatives d'inscription par heure par IP
RateLimiter::tooManyAttempts($key, 3)
```

### **3. Validation Stricte**
```php
// Jeunes
'date_naissance' => ['required', 'date', 'before:-16 years']
'email' => ['required', 'string', 'email', 'max:100', 'unique:users']
'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()]

// Employeurs
'numero_rccm' => ['nullable', 'string', 'max:50', 'unique:employeurs']
'numero_ninea' => ['nullable', 'string', 'max:50', 'unique:employeurs']
```

### **4. reCAPTCHA v2**
```html
<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
```

### **5. Validation AJAX en Temps Réel**
- Vérification disponibilité email
- Vérification disponibilité RCCM/NINEA
- Feedback visuel immédiat

## 🎨 Interface Utilisateur

### **Design Moderne**
- Layout en deux colonnes (formulaire + image)
- Animations fluides et transitions CSS
- Design responsive (mobile-first)
- Cartes flottantes interactives

### **Formulaires Organisés**
- **Sections thématiques** : Informations personnelles, adresse, éducation, etc.
- **Validation en temps réel** : Feedback visuel immédiat
- **Champs conditionnels** : Affichage selon le type de compte
- **Messages d'erreur contextuels** : Précision et clarté

### **Expérience Utilisateur**
- **Sélecteur de type** : Jeune/Employeur avec icônes
- **Toggle mot de passe** : Visibilité du mot de passe
- **Loading overlay** : Indicateur de progression
- **Messages toast** : Notifications non-intrusives

## 📱 Responsive Design

### **Breakpoints**
- **Desktop** : Layout en deux colonnes
- **Tablet** : Adaptation du layout
- **Mobile** : Layout en une colonne, cartes masquées

### **Optimisations Mobile**
- Formulaire en pleine largeur
- Boutons et inputs optimisés pour le tactile
- Navigation simplifiée
- reCAPTCHA adapté

## 🔧 Configuration

### **Variables d'Environnement**
```env
# reCAPTCHA
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
RECAPTCHA_VERSION=v2

# Sécurité
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### **Routes**
```php
// Inscription
Route::get('register', [RegistrationController::class, 'showRegistrationForm']);
Route::post('register/jeune', [RegistrationController::class, 'registerJeune']);
Route::post('register/employeur', [RegistrationController::class, 'registerEmployeur']);

// Vérifications AJAX
Route::post('check-email', [RegistrationController::class, 'checkEmail']);
Route::post('check-rccm', [RegistrationController::class, 'checkRccm']);
Route::post('check-ninea', [RegistrationController::class, 'checkNinea']);
```

## 📊 Validation des Données

### **Jeunes - Champs Requis**
- Prénom, nom, email, téléphone
- Date de naissance (minimum 16 ans)
- Genre, lieu de naissance, nationalité
- Adresse complète (adresse, ville, région, pays)
- Niveau d'étude
- Mot de passe (8+ caractères, complexité requise)

### **Jeunes - Champs Optionnels**
- Numéro CNI
- Dernier diplôme, établissement, année d'obtention
- Programme SOS, catégorie
- Contact d'urgence

### **Employeurs - Champs Requis**
- Prénom, nom, email, téléphone
- Nom de l'entreprise
- Secteur d'activité
- Taille et type d'entreprise
- Adresse complète de l'entreprise
- Mot de passe (8+ caractères, complexité requise)

### **Employeurs - Champs Optionnels**
- Raison sociale
- Numéros RCCM, NINEA, contribuable
- Description de l'activité
- Contact RH
- Site web, email entreprise

## 🚀 Workflow d'Activation

### **1. Inscription**
```php
// Création utilisateur avec statut inactif
$user = User::create([
    'role' => 'jeune', // ou 'employeur'
    'statut' => 'inactif',
    'email_verified_at' => null
]);
```

### **2. Validation Administrative**
```php
// Par admin local (jeunes de son programme)
// Par admin national (tous les comptes)
$user->update([
    'statut' => 'actif',
    'valide_par' => $admin->user_id,
    'valide_le' => now(),
    'commentaire_validation' => 'Compte validé'
]);
```

### **3. Accès au Dashboard**
- Redirection automatique selon le rôle
- Accès aux fonctionnalités spécifiques

## 📈 Monitoring et Logs

### **Logs d'Inscription**
```php
Log::info('Nouvelle inscription jeune', [
    'user_id' => $user->user_id,
    'email' => $user->email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent(),
]);
```

### **Logs de Sécurité**
```php
Log::error('Erreur lors de l\'inscription', [
    'error' => $e->getMessage(),
    'email' => $request->email,
    'ip' => $request->ip(),
]);
```

## 🔍 Tests Recommandés

### **Tests de Sécurité**
```php
// Test CSRF protection
public function test_csrf_protection()

// Test rate limiting
public function test_registration_rate_limiting()

// Test validation des données
public function test_jeune_validation()
public function test_employeur_validation()

// Test reCAPTCHA
public function test_recaptcha_validation()
```

### **Tests Fonctionnels**
```php
// Test inscription jeune
public function test_jeune_registration()

// Test inscription employeur
public function test_employeur_registration()

// Test vérifications AJAX
public function test_email_availability_check()
```

## 🛠️ Maintenance

### **Tâches Régulières**
1. **Audit des logs** : Vérification des tentatives d'inscription
2. **Mise à jour reCAPTCHA** : Renouvellement des clés
3. **Validation des comptes** : Traitement des demandes en attente
4. **Nettoyage des données** : Suppression des comptes inactifs anciens

### **Métriques à Surveiller**
- **Taux de conversion** : Inscriptions → Validations
- **Temps de validation** : Délai moyen d'activation
- **Tentatives échouées** : Rate limiting activé
- **Erreurs de validation** : Champs problématiques

## 🚨 Gestion des Incidents

### **Compte Non Activé**
- Vérifier les logs de validation
- Contacter l'administrateur concerné
- Vérifier les critères de validation

### **Erreur reCAPTCHA**
- Vérifier les clés de configuration
- Tester la connectivité Google
- Vérifier les logs de validation

### **Problème de Validation**
- Vérifier les règles de validation
- Contrôler les données soumises
- Vérifier les contraintes de base de données

## 📞 Support

### **En Cas de Problème**
1. Vérifier les logs Laravel : `storage/logs/laravel.log`
2. Contrôler la configuration reCAPTCHA
3. Vérifier les variables d'environnement
4. Tester les routes d'inscription

### **Contact**
- Vérifier d'abord la documentation
- Consulter les logs de sécurité
- Tester en environnement de développement

---

**Note importante :** Ce système d'inscription respecte les meilleures pratiques de sécurité et d'UX. La validation administrative garantit la qualité des comptes tout en maintenant la sécurité de la plateforme. 
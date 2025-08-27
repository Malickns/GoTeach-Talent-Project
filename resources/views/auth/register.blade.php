<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoTeach - Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- reCAPTCHA supprimé -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth/register.css', 'resources/js/auth/register.js'])
</head>
<body>
    <div class="container">
        <!-- Section gauche avec le formulaire -->
        <div class="left-section">
            <div class="form-container">
                <div class="logo-container">
                    <div class="logo">
                        <span class="logo-text">GoTeach</span>
                        <span class="logo-hash">#</span>
                    </div>
                    <p class="welcome-text">Rejoignez la communauté GoTeach</p>
                </div>

                <!-- Sélecteur de type de compte -->
                <div class="account-type-selector">
                    <button type="button" class="type-btn active" data-type="jeune">
                        <i class="fas fa-user-graduate"></i>
                        <span>Jeune</span>
                    </button>
                    <button type="button" class="type-btn" data-type="employeur">
                        <i class="fas fa-building"></i>
                        <span>Employeur</span>
                    </button>
                </div>

                <!-- Formulaire d'inscription Jeune -->
                <form class="registration-form active" id="jeuneForm" method="POST" action="{{ route('register.jeune') }}">
                    @csrf
                    
                    <!-- Informations personnelles -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i>
                            Informations personnelles
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_prenom">Prénom *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" id="jeune_prenom" name="prenom" value="{{ old('prenom') }}" required>
                                </div>
                                @error('prenom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_nom">Nom *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" id="jeune_nom" name="nom" value="{{ old('nom') }}" required>
                                </div>
                                @error('nom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_date_naissance">Date de naissance *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar input-icon"></i>
                                    <input type="date" id="jeune_date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                </div>
                                @error('date_naissance')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_genre">Genre *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-venus-mars input-icon"></i>
                                    <select id="jeune_genre" name="genre" required>
                                        <option value="">Sélectionner</option>
                                        <option value="homme" {{ old('genre') == 'homme' ? 'selected' : '' }}>Homme</option>
                                        <option value="femme" {{ old('genre') == 'femme' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                </div>
                                @error('genre')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_lieu_naissance">Lieu de naissance *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text" id="jeune_lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance') }}" required>
                                </div>
                                @error('lieu_naissance')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_nationalite">Nationalité *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-flag input-icon"></i>
                                    <input type="text" id="jeune_nationalite" name="nationalite" value="{{ old('nationalite', 'Sénégalais') }}" required>
                                </div>
                                @error('nationalite')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jeune_numero_cni">Numéro CNI (optionnel)</label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="text" id="jeune_numero_cni" name="numero_cni" value="{{ old('numero_cni') }}">
                            </div>
                            @error('numero_cni')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-home"></i>
                            Adresse
                        </h3>
                        
                        <div class="form-group">
                            <label for="jeune_adresse">Adresse complète *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" id="jeune_adresse" name="adresse" value="{{ old('adresse') }}" required>
                            </div>
                            @error('adresse')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_ville">Ville *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-city input-icon"></i>
                                    <input type="text" id="jeune_ville" name="ville" value="{{ old('ville') }}" required>
                                </div>
                                @error('ville')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_region">Région *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map input-icon"></i>
                                    <input type="text" id="jeune_region" name="region" value="{{ old('region') }}" required>
                                </div>
                                @error('region')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jeune_pays">Pays *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-globe input-icon"></i>
                                <input type="text" id="jeune_pays" name="pays" value="{{ old('pays', 'Sénégal') }}" required>
                            </div>
                            @error('pays')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Éducation -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap"></i>
                            Éducation et formation
                        </h3>
                        
                        <div class="form-group">
                            <label for="jeune_niveau_etude">Niveau d'étude *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-graduation-cap input-icon"></i>
                                <select id="jeune_niveau_etude" name="niveau_etude" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Primaire" {{ old('niveau_etude') == 'Primaire' ? 'selected' : '' }}>Primaire</option>
                                    <option value="Collège" {{ old('niveau_etude') == 'Collège' ? 'selected' : '' }}>Collège</option>
                                    <option value="Lycée" {{ old('niveau_etude') == 'Lycée' ? 'selected' : '' }}>Lycée</option>
                                    <option value="Bac" {{ old('niveau_etude') == 'Bac' ? 'selected' : '' }}>Bac</option>
                                    <option value="Bac+1" {{ old('niveau_etude') == 'Bac+1' ? 'selected' : '' }}>Bac+1</option>
                                    <option value="Bac+2" {{ old('niveau_etude') == 'Bac+2' ? 'selected' : '' }}>Bac+2</option>
                                    <option value="Bac+3" {{ old('niveau_etude') == 'Bac+3' ? 'selected' : '' }}>Bac+3</option>
                                    <option value="Bac+4" {{ old('niveau_etude') == 'Bac+4' ? 'selected' : '' }}>Bac+4</option>
                                    <option value="Bac+5" {{ old('niveau_etude') == 'Bac+5' ? 'selected' : '' }}>Bac+5</option>
                                    <option value="Doctorat" {{ old('niveau_etude') == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                                </select>
                            </div>
                            @error('niveau_etude')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_dernier_diplome">Dernier diplôme obtenu</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-certificate input-icon"></i>
                                    <input type="text" id="jeune_dernier_diplome" name="dernier_diplome" value="{{ old('dernier_diplome') }}">
                                </div>
                                @error('dernier_diplome')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_annee_obtention">Année d'obtention</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="number" id="jeune_annee_obtention" name="annee_obtention" value="{{ old('annee_obtention') }}" min="1950" max="{{ date('Y') + 1 }}">
                                </div>
                                @error('annee_obtention')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jeune_etablissement">Établissement</label>
                            <div class="input-wrapper">
                                <i class="fas fa-university input-icon"></i>
                                <input type="text" id="jeune_etablissement" name="etablissement" value="{{ old('etablissement') }}">
                            </div>
                            @error('etablissement')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Programme SOS -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-heart"></i>
                            Programme SOS (optionnel)
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_programme_id">Programme</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-users input-icon"></i>
                                    <select id="jeune_programme_id" name="programme_id">
                                        <option value="">Sélectionner un programme</option>
                                        @foreach($programmes as $programme)
                                            <option value="{{ $programme->programme_id }}" {{ old('programme_id') == $programme->programme_id ? 'selected' : '' }}>
                                                {{ $programme->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('programme_id')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_categorie_id">Catégorie</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-tag input-icon"></i>
                                    <select id="jeune_categorie_id" name="categorie_id">
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->categorie_id }}" {{ old('categorie_id') == $categorie->categorie_id ? 'selected' : '' }}>
                                                {{ $categorie->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('categorie_id')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-phone"></i>
                            Contact d'urgence (optionnel)
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_contact_urgence_nom">Nom du contact</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" id="jeune_contact_urgence_nom" name="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}">
                                </div>
                                @error('contact_urgence_nom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_contact_urgence_telephone">Téléphone</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="tel" id="jeune_contact_urgence_telephone" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone') }}">
                                </div>
                                @error('contact_urgence_telephone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jeune_contact_urgence_relation">Relation</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user-friends input-icon"></i>
                                <input type="text" id="jeune_contact_urgence_relation" name="contact_urgence_relation" value="{{ old('contact_urgence_relation') }}" placeholder="ex: Parent, Tuteur, etc.">
                            </div>
                            @error('contact_urgence_relation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations de connexion -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-lock"></i>
                            Informations de connexion
                        </h3>
                        
                        <div class="form-group">
                            <label for="jeune_email">Adresse email *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="jeune_email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jeune_telephone">Téléphone *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="jeune_telephone" name="telephone" value="{{ old('telephone') }}" required>
                            </div>
                            @error('telephone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jeune_password">Mot de passe *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="jeune_password" name="password" required>
                                    <button type="button" class="password-toggle" data-target="jeune_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jeune_password_confirmation">Confirmer le mot de passe *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="jeune_password_confirmation" name="password_confirmation" required>
                                    <button type="button" class="password-toggle" data-target="jeune_password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="form-section">
                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="terms" required>
                                <span class="checkmark"></span>
                                J'accepte les <a href="#" class="terms-link">conditions d'utilisation</a> et la <a href="#" class="terms-link">politique de confidentialité</a> *
                            </label>
                            @error('terms')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Captcha supprimé -->
                    </div>

                    <button type="submit" class="register-btn" id="jeuneSubmitBtn">
                        <span class="btn-text">Créer mon compte jeune</span>
                        <i class="fas fa-arrow-right btn-icon"></i>
                    </button>
                </form>

                <!-- Formulaire d'inscription Employeur -->
                <form class="registration-form" id="employeurForm" method="POST" action="{{ route('register.employeur') }}">
                    @csrf
                    
                    <!-- Informations personnelles -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i>
                            Informations personnelles
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_prenom">Prénom *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" id="employeur_prenom" name="prenom" value="{{ old('prenom') }}" required>
                                </div>
                                @error('prenom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_nom">Nom *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" id="employeur_nom" name="nom" value="{{ old('nom') }}" required>
                                </div>
                                @error('nom')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations entreprise -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-building"></i>
                            Informations entreprise
                        </h3>
                        
                        <div class="form-group">
                            <label for="employeur_nom_entreprise">Nom de l'entreprise *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-building input-icon"></i>
                                <input type="text" id="employeur_nom_entreprise" name="nom_entreprise" value="{{ old('nom_entreprise') }}" required>
                            </div>
                            @error('nom_entreprise')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="employeur_raison_sociale">Raison sociale</label>
                            <div class="input-wrapper">
                                <i class="fas fa-file-contract input-icon"></i>
                                <input type="text" id="employeur_raison_sociale" name="raison_sociale" value="{{ old('raison_sociale') }}">
                            </div>
                            @error('raison_sociale')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_numero_rccm">Numéro RCCM</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input type="text" id="employeur_numero_rccm" name="numero_rccm" value="{{ old('numero_rccm') }}">
                                </div>
                                @error('numero_rccm')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_numero_ninea">Numéro NINEA</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input type="text" id="employeur_numero_ninea" name="numero_ninea" value="{{ old('numero_ninea') }}">
                                </div>
                                @error('numero_ninea')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="employeur_secteur_activite">Secteur d'activité *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-industry input-icon"></i>
                                <input type="text" id="employeur_secteur_activite" name="secteur_activite" value="{{ old('secteur_activite') }}" required>
                            </div>
                            @error('secteur_activite')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="employeur_description_activite">Description de l'activité</label>
                            <div class="input-wrapper">
                                <i class="fas fa-align-left input-icon"></i>
                                <textarea id="employeur_description_activite" name="description_activite" rows="3">{{ old('description_activite') }}</textarea>
                            </div>
                            @error('description_activite')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_taille_entreprise">Taille de l'entreprise *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-users input-icon"></i>
                                    <select id="employeur_taille_entreprise" name="taille_entreprise" required>
                                        <option value="">Sélectionner</option>
                                        <option value="micro" {{ old('taille_entreprise') == 'micro' ? 'selected' : '' }}>Micro (1-10 employés)</option>
                                        <option value="petite" {{ old('taille_entreprise') == 'petite' ? 'selected' : '' }}>Petite (11-50 employés)</option>
                                        <option value="moyenne" {{ old('taille_entreprise') == 'moyenne' ? 'selected' : '' }}>Moyenne (51-250 employés)</option>
                                        <option value="grande" {{ old('taille_entreprise') == 'grande' ? 'selected' : '' }}>Grande (250+ employés)</option>
                                    </select>
                                </div>
                                @error('taille_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_type_entreprise">Type d'entreprise *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-briefcase input-icon"></i>
                                    <select id="employeur_type_entreprise" name="type_entreprise" required>
                                        <option value="">Sélectionner</option>
                                        <option value="privee" {{ old('type_entreprise') == 'privee' ? 'selected' : '' }}>Privée</option>
                                        <option value="publique" {{ old('type_entreprise') == 'publique' ? 'selected' : '' }}>Publique</option>
                                        <option value="ong" {{ old('type_entreprise') == 'ong' ? 'selected' : '' }}>ONG</option>
                                        <option value="cooperative" {{ old('type_entreprise') == 'cooperative' ? 'selected' : '' }}>Coopérative</option>
                                        <option value="autre" {{ old('type_entreprise') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                                @error('type_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Adresse entreprise -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Adresse de l'entreprise
                        </h3>
                        
                        <div class="form-group">
                            <label for="employeur_adresse_entreprise">Adresse complète *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" id="employeur_adresse_entreprise" name="adresse_entreprise" value="{{ old('adresse_entreprise') }}" required>
                            </div>
                            @error('adresse_entreprise')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_ville_entreprise">Ville *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-city input-icon"></i>
                                    <input type="text" id="employeur_ville_entreprise" name="ville_entreprise" value="{{ old('ville_entreprise') }}" required>
                                </div>
                                @error('ville_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_region_entreprise">Région *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map input-icon"></i>
                                    <input type="text" id="employeur_region_entreprise" name="region_entreprise" value="{{ old('region_entreprise') }}" required>
                                </div>
                                @error('region_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_pays_entreprise">Pays *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-globe input-icon"></i>
                                    <input type="text" id="employeur_pays_entreprise" name="pays_entreprise" value="{{ old('pays_entreprise', 'Sénégal') }}" required>
                                </div>
                                @error('pays_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_code_postal_entreprise">Code postal</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-mail-bulk input-icon"></i>
                                    <input type="text" id="employeur_code_postal_entreprise" name="code_postal_entreprise" value="{{ old('code_postal_entreprise') }}">
                                </div>
                                @error('code_postal_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact entreprise -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-phone"></i>
                            Contact entreprise
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_telephone_entreprise">Téléphone entreprise</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="tel" id="employeur_telephone_entreprise" name="telephone_entreprise" value="{{ old('telephone_entreprise') }}">
                                </div>
                                @error('telephone_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_email_entreprise">Email entreprise</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" id="employeur_email_entreprise" name="email_entreprise" value="{{ old('email_entreprise') }}">
                                </div>
                                @error('email_entreprise')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="employeur_site_web">Site web</label>
                            <div class="input-wrapper">
                                <i class="fas fa-globe input-icon"></i>
                                <input type="url" id="employeur_site_web" name="site_web" value="{{ old('site_web') }}" placeholder="https://www.exemple.com">
                            </div>
                            @error('site_web')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact RH -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user-tie"></i>
                            Contact RH (optionnel)
                        </h3>
                        
                        <div class="form-group">
                            <label for="employeur_contact_rh_nom">Nom du contact RH</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="employeur_contact_rh_nom" name="contact_rh_nom" value="{{ old('contact_rh_nom') }}">
                            </div>
                            @error('contact_rh_nom')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_contact_rh_telephone">Téléphone RH</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="tel" id="employeur_contact_rh_telephone" name="contact_rh_telephone" value="{{ old('contact_rh_telephone') }}">
                                </div>
                                @error('contact_rh_telephone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_contact_rh_email">Email RH</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" id="employeur_contact_rh_email" name="contact_rh_email" value="{{ old('contact_rh_email') }}">
                                </div>
                                @error('contact_rh_email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations de connexion -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-lock"></i>
                            Informations de connexion
                        </h3>
                        
                        <div class="form-group">
                            <label for="employeur_email">Adresse email *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="employeur_email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="employeur_telephone">Téléphone *</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="employeur_telephone" name="telephone" value="{{ old('telephone') }}" required>
                            </div>
                            @error('telephone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="employeur_password">Mot de passe *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="employeur_password" name="password" required>
                                    <button type="button" class="password-toggle" data-target="employeur_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="employeur_password_confirmation">Confirmer le mot de passe *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="employeur_password_confirmation" name="password_confirmation" required>
                                    <button type="button" class="password-toggle" data-target="employeur_password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="form-section">
                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="terms" required>
                                <span class="checkmark"></span>
                                J'accepte les <a href="#" class="terms-link">conditions d'utilisation</a> et la <a href="#" class="terms-link">politique de confidentialité</a> *
                            </label>
                            @error('terms')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Captcha supprimé -->
                    </div>

                    <button type="submit" class="register-btn" id="employeurSubmitBtn">
                        <span class="btn-text">Créer mon compte employeur</span>
                        <i class="fas fa-arrow-right btn-icon"></i>
                    </button>
                </form>

                <div class="login-section">
                    <p>Vous avez déjà un compte ? <a href="{{ route('login') }}" class="login-link">Se connecter</a></p>
                </div>
            </div>
        </div>

        <!-- Section droite avec l'image et les éléments décoratifs -->
        <div class="right-section" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.3) 0%, rgba(51, 65, 85, 0.4) 100%), url('{{ asset('images/auth/login_background.png') }}');">
            <div class="background-overlay"></div>
            <div class="decorative-elements">
                <div class="floating-card card-1">
                    <i class="fas fa-user-plus"></i>
                    <span>Inscription</span>
                </div>
                <div class="floating-card card-2">
                    <i class="fas fa-handshake"></i>
                    <span>Opportunités</span>
                </div>
                <div class="floating-card card-3">
                    <i class="fas fa-rocket"></i>
                    <span>Carrière</span>
                </div>
            </div>
            <div class="hero-content">
                <h1>Rejoignez la communauté GoTeach</h1>
                <p>Créez votre compte et accédez à des opportunités uniques pour votre avenir professionnel</p>
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <p>Création du compte en cours...</p>
    </div>
</body>
</html>
